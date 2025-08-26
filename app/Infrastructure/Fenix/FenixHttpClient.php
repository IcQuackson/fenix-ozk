<?php
namespace App\Infrastructure\Fenix;

use App\Contracts\FenixPort;
use App\Contracts\TokenProvider;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

final class FenixHttpClient implements FenixPort
{
    public function __construct(
        private string $baseUrl,
        private string $accessTokenUrl,
        private string $refreshTokenUrl,
        private string $clientId,
        private string $clientSecret,
        private TokenProvider $tokens,
        private CacheRepository $cache,
    ) {
    }

    // --- About & Metadata (PUBLIC) ---
    public function getAbout(?string $lang = null): array
    {
        return $this->getPublic('/about', compact('lang'));
    }
    public function listAcademicTerms(?string $lang = null): array
    {
        return $this->getPublic('/academicterms', compact('lang'));
    }
    public function getDomainModel(?string $lang = null): array
    {
        return $this->getPublic('/domainModel', compact('lang'));
    }

    // --- Contacts & Facilities (PUBLIC) ---
    public function getCanteenMenu(?string $lang = null): array
    {
        return $this->getPublic('/canteen', compact('lang'));
    }
    public function getContacts(?string $lang = null): array
    {
        return $this->getPublic('/contacts', compact('lang'));
    }
    public function getParking(?string $lang = null): array
    {
        return $this->getPublic('/parking', compact('lang'));
    }
    public function getShuttle(?string $lang = null): array
    {
        return $this->getPublic('/shuttle', compact('lang'));
    }

    // --- Courses (PUBLIC) ---
    public function getCourseById(string $id, ?string $lang = null): array
    {
        return $this->getPublic("/courses/{$id}", compact('lang'));
    }
    public function listCourseEvaluations(string $id, ?string $lang = null): array
    {
        return $this->getPublic("/courses/{$id}/evaluations", compact('lang'));
    }
    public function listCourseGroups(string $id, ?string $lang = null): array
    {
        return $this->getPublic("/courses/{$id}/groups", compact('lang'));
    }
    public function getCourseSchedule(string $id, ?string $lang = null): array
    {
        return $this->getPublic("/courses/{$id}/schedule", compact('lang'));
    }
    public function listCourseStudents(string $id, ?string $lang = null): array
    {
        return $this->getPublic("/courses/{$id}/students", compact('lang'));
    }

    // --- Degrees (PUBLIC) ---
    public function listDegrees(?string $term = null, ?string $lang = null): array
    {
        return $this->getPublic('/degrees', array_filter(compact('term', 'lang')));
    }
    public function getDegreeById(string $id, ?string $term = null, ?string $lang = null): array
    {
        return $this->getPublic("/degrees/{$id}", array_filter(compact('term', 'lang')));
    }
    public function listDegreeCourses(string $id, ?string $term = null, ?string $lang = null): array
    {
        return $this->getPublic("/degrees/{$id}/courses", array_filter(compact('term', 'lang')));
    }

    // --- Spaces (PUBLIC) ---
    public function listSpaces(?string $lang = null): array
    {
        return $this->getPublic('/spaces', compact('lang'));
    }
    public function getSpaceById(string $id, ?string $day = null, ?string $lang = null): array
    {
        return $this->getPublic("/spaces/{$id}", array_filter(compact('day', 'lang')));
    }
    public function getSpaceBlueprint(string $id, string $format, ?string $lang = null): mixed
    {
        return $this->getPublic("/spaces/{$id}/blueprint", array_filter(compact('format', 'lang')));
    }

    // --- People (PRIVATE) ---
    public function getPerson(int $userId, ?string $lang = null): array
    {
        return $this->get($userId, '/person', compact('lang'));
    }
    public function getPersonCalendarClasses(int $userId, ?string $format = 'json', ?string $lang = null): array|string
    {
        return $this->get($userId, '/person/calendar/classes', array_filter(['format' => $format, 'lang' => $lang]));
    }
    public function getPersonCalendarEvaluations(int $userId, ?string $format = 'json', ?string $lang = null): array|string
    {
        return $this->get($userId, '/person/calendar/evaluations', array_filter(['format' => $format, 'lang' => $lang]));
    }
    public function getPersonCourses(int $userId, ?string $term = null, ?string $lang = null): array
    {
        return $this->get($userId, '/person/courses', array_filter(compact('term', 'lang')));
    }
    public function getPersonCurriculum(int $userId, ?string $lang = null): array
    {
        return $this->get($userId, '/person/curriculum', compact('lang'));
    }
    public function getPersonEvaluations(int $userId, ?string $lang = null): array
    {
        return $this->get($userId, '/person/evaluations', compact('lang'));
    }
    public function updatePersonEvaluationEnrolment(int $userId, string $id, bool $enrol, ?string $lang = null): array
    {
        $query = array_filter(['enrol' => $enrol ? 'yes' : 'no', 'lang' => $lang]);
        return $this->put($userId, "/person/evaluations/{$id}", $query);
    }
    public function getPersonPayments(int $userId, ?string $lang = null): array
    {
        return $this->get($userId, '/person/payments', compact('lang'));
    }

    // --- Helpers for PRIVATE endpoints ---
    private function get(int $userId, string $path, array $query = []): array|string
    {
        $token = $this->tokens->forUser($userId);
        if (!$token)
            throw new \RuntimeException('Fenix token missing');

        $resp = Http::withToken($token)
            ->retry(2, 250)
            ->timeout(10)
            ->acceptJson()
            ->get(rtrim($this->baseUrl, '/') . $path, $query);

        if ($resp->status() === 401) {
            $token = $this->tokens->refreshForUser($userId);
            $resp = Http::withToken($token)->get(rtrim($this->baseUrl, '/') . $path, $query);
        }

        try {
            $resp->throw();
        } catch (RequestException $e) {
            throw new \RuntimeException('Fenix API error: ' . $e->getMessage(), previous: $e);
        }

        return $resp->header('content-type') === 'text/calendar'
            ? $resp->body()
            : ($resp->json() ?? []);
    }

    // Helpers for PUBLIC endpoints
    private function getPublic(string $path, array $query = []): array|string
    {
        $resp = Http::retry(2, 250)
            ->timeout(10)
            ->acceptJson()
            ->get(rtrim($this->baseUrl, '/') . $path, $query);

        $resp->throw();
        return $resp->header('content-type') === 'text/calendar'
            ? $resp->body()
            : ($resp->json() ?? []);
    }

    private function put(int $userId, string $path, array $query = []): array
    {
        $token = $this->tokens->forUser($userId);
        if (!$token)
            throw new \RuntimeException('Fenix token missing');

        $resp = Http::withToken($token)
            ->retry(2, 250)
            ->timeout(10)
            ->acceptJson()
            ->put(rtrim($this->baseUrl, '/') . $path, $query);

        if ($resp->status() === 401) {
            $token = $this->tokens->refreshForUser($userId);
            $resp = Http::withToken($token)->put(rtrim($this->baseUrl, '/') . $path, $query);
        }

        return $resp->throw()->json();
    }
}
