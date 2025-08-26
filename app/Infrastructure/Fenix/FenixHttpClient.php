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
    ) {}

    public function listCourses(int $userId): array
    {
        return $this->get($userId, '/person/courses');
    }

    public function listGrades(int $userId): array
    {
        return $this->get($userId, '/person/curriculum')['courses'] ?? [];
    }

    public function me(int $userId): array
    {
        return $this->get($userId, '/person');
    }

    /** @return array<string,mixed>|array<int,mixed> */
    private function get(int $userId, string $path): array
    {
        $token = $this->tokens->forUser($userId);
        if (!$token) throw new \RuntimeException('Fenix token missing');

        $resp = Http::withToken($token)
            ->retry(2, 250)
            ->timeout(10)
            ->acceptJson()
            ->get(rtrim($this->baseUrl, '/').$path);

        if ($resp->status() === 401) {
            // Try refresh once
            $token = $this->tokens->refreshForUser($userId);
            if (!$token) throw new \RuntimeException('Unable to refresh Fenix token');
            $resp = Http::withToken($token)->retry(1, 200)->timeout(10)->acceptJson()
                ->get(rtrim($this->baseUrl, '/').$path);
        }

        try {
            $resp->throw();
        } catch (RequestException $e) {
            throw new \RuntimeException('Fenix API error: '.$e->getMessage(), previous: $e);
        }

        return $resp->json() ?? [];
    }
}
