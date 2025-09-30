<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\Course;
use App\Domain\Entities\CourseAnnouncement;
use App\Domain\Entities\CourseEvaluation;
use App\Domain\Entities\Curriculum;
use App\Domain\Entities\Person;
use App\Domain\Entities\CurriculumCollection;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Log;

final class PersonService
{
    public function __construct(
        private FenixPort $fenix,
        private CacheRepository $cache,
        private InstitutionService $institutionService,
        private CourseService $courseService,
    ) {}

    public function getPerson(int $userId): ?Person
    {
        $key = "person:{$userId}:name:v1";

        $raw = $this->cache->remember(
            $key,
            now()->addMinutes(5),
            fn () => $this->fenix->getPerson($userId)
        );

        Log::debug('Fenix API person response', ['raw' => $raw]);

        return is_array($raw) ? Person::fromApi($raw) : null;
    }

    /** @return CourseEvaluation[] */
    public function upcomingEvaluations(int $userId): array
    {
        $key = "person:{$userId}:evaluations:v1";
        $raw = $this->cache->remember(
            $key,
            now()->addMinutes(5),
            fn () => $this->fenix->getPersonEvaluations($userId)
        );

        $evaluations = array_map(fn ($r) => CourseEvaluation::fromApi($r), $raw);

        // sort by date, then filter only future ones
        return collect($evaluations)
            ->filter(fn (CourseEvaluation $e) => $e->examAt && $e->examAt->isFuture())
            ->sortBy(fn (CourseEvaluation $e) => $e->examAt)
            ->values()
            ->all();
    }

    public function getEnrolledCoursesByTerm(int $userId, string $term): array
    {
        $raw = $this->fenix->getPersonCourses($userId, $term);

        $courses = array_map(fn ($c) => Course::fromApi($c), $raw['enrolments'] ?? []);

        return $courses;
    }

    public function getCurrentEnrolledCourses(int $userId): array
    {
        $term = $this->institutionService->currentAcademicTerm();

        return $this->getEnrolledCoursesByTerm($userId, $term);
    }

    public function getCurrentCoursesAnnouncements(int $userId): array
    {
        $courses = $this->getCurrentEnrolledCourses($userId);

        $announcements = [];

        foreach ($courses as $course) {
            try {
                $courseAnnouncements = $this->courseService->listAnnouncements($course->id);

                foreach ($courseAnnouncements as $a) {
                    $a->courseName = $course->name;

                    $announcements[] = $a;
                }

            } catch (\Throwable $e) {
                // log but don't break other courses
                \Log::warning("Failed to fetch announcements for course {$course->id}", [
                    'exception' => $e,
                ]);
            }
        }

        // sort by publication date (descending)
        usort(
            $announcements,
            function (CourseAnnouncement $a, CourseAnnouncement $b): int {
                return $b->publishedAt->getTimestamp() <=> $a->publishedAt->getTimestamp();
            }
        );

        return $announcements;
    }

    public function getLatestCurriculum(int $userId): ?Curriculum
    {
        $key = "person:{$userId}:curriculum:v1";

        $raw = $this->cache->remember(
            $key,
            now()->addMinutes(5),
            fn () => $this->fenix->getPersonCurriculum($userId)
        );

        // Optional debug logging (keeps parity with your other method)
        Log::debug('Fenix API curriculum response', ['raw' => $raw]);

        $collection = CurriculumCollection::fromApi(is_array($raw) ? $raw : []);

        return $collection->latest();
    }
}
