<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\Course;
use App\Domain\Entities\CourseEvaluation;
use App\Domain\Entities\Curriculum;
use App\Domain\Entities\Person;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class DashboardService
{
    public function __construct(
        private FenixPort $fenix,
        private CacheRepository $cache,
        private PersonService $personService,
        private InstitutionService $institutionService
    ) {}

    /** @return array<string,mixed>|null */
    public function getMe(int $userId): ?Person
    {
        $cacheKey = "dashboard:me:{$userId}:v1";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboard:me:{$userId}", 10);

            return $lock->block(5, fn () => $this->personService->getPerson($userId));
        });
    }

    public function getEctsSum(int $userId): float
    {
        $cacheKey = "dashboard:ectsSum:{$userId}:v1";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboard:ectsSum:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {
                $curriculum = $this->fenix->getPersonCurriculum($userId);

                return collect($curriculum)->sum(fn ($c) => (float) ($c['ects'] ?? 0));
            });
        });
    }

    /** @return CourseEvaluation[] */
    public function getUpcomingEvaluations(int $userId): array
    {
        $cacheKey = "dashboard:upcomingEvaluations:{$userId}:v1";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboard:upcomingEvaluations:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {
                $rawEvals = $this->fenix->getPersonEvaluations($userId);

                $evaluations = array_map(fn ($r) => CourseEvaluation::fromApi($r), (array) $rawEvals);

                return collect($evaluations)
                    ->filter(fn (CourseEvaluation $e) => $e->evaluationPeriod->start->isFuture())
                    ->sortBy(fn (CourseEvaluation $e) => $e->evaluationPeriod->start->getTimestamp())
                    ->values()
                    ->all();
            });
        });
    }

    /**
     * @return array{courses:Course[]}
     */
    public function getCurrentEnrolledCourses(int $userId): array
    {
        $cacheKey = "dashboard:currentEnrolledCourses:{$userId}:v2";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboard:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {

                return $this->personService->getCurrentEnrolledCourses($userId);
            });
        });
    }

    public function listAnnouncements(int $userId): array
    {
        $cacheKey = "dashboard:listAnnouncements:{$userId}:v2";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboard:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {

                return $this->personService->getCurrentCoursesAnnouncements($userId);
            });
        });
    }

    public function getLatestCurriculum(int $userId): ?Curriculum
    {
        $cacheKey = "dashboard:latestCurriculum:{$userId}:v1";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboard:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {
                return $this->personService->getLatestCurriculum($userId);
            });
        });
    }

    /**
     * @return array{
     *   degree: array{id:?string,acronym:?string,name:?string},
     *   kpis: array{
     *     totalEcts: float,
     *     avgGrade: ?float,
     *     ectsThisTerm: float,
     *     ectsPerYear: ?float
     *   }
     * }
     */
    public function curriculumKpis(int $userId): array
    {
        $cacheKey = "dashboard:curriculumKpis:{$userId}:v1";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboardKpis:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {
                $curriculum = $this->personService->getLatestCurriculum($userId);

                if (! $curriculum) {
                    return [
                        'degree' => ['id' => null, 'acronym' => null, 'name' => null],
                        'kpis' => [
                            'totalEcts' => 0.0,
                            'avgGrade' => null,
                            'ectsThisTerm' => 0.0,
                            'ectsPerYear' => null,
                        ],
                    ];
                }

                // 1) Total earned ECTS
                $totalEcts = (float) $curriculum->credits;

                // 2) Average grade (prefer calculatedAverage)
                $avgGrade = $curriculum->average ?: null;
                $avgGrade = $avgGrade !== null ? (float) $avgGrade : null;

                // 3) ECTS earned in the current academic term
                $term = $this->institutionService->currentAcademicTerm();
                $ectsThisTerm = 0.0;
                foreach ($curriculum->approvedCourses as $course) {
                    if ($course->academicTerm === $term && $course->ects !== null) {
                        $ectsThisTerm += (float) $course->ects;
                    }
                }

                // 4) ECTS per year (pace)
                $start = $curriculum->start ? Carbon::instance($curriculum->start) : null;
                $endRef = $curriculum->end ? Carbon::instance($curriculum->end) : now();
                $years = null;
                if ($start) {
                    $days = max(1, $start->diffInDays($endRef)); // avoid divide-by-zero
                    $years = $days / 365.25;
                }
                $ectsPerYear = $years ? $totalEcts / $years : null;

                return [
                    'degree' => [
                        'id' => $curriculum->degreeId,
                        'acronym' => $curriculum->degreeAcronym,
                        'name' => $curriculum->degreeName,
                    ],
                    'kpis' => [
                        'totalEcts' => round($totalEcts, 2),
                        'avgGrade' => $avgGrade !== null ? round($avgGrade, 2) : null,
                        'ectsThisTerm' => round($ectsThisTerm, 2),
                        'ectsPerYear' => $ectsPerYear !== null ? round($ectsPerYear, 2) : null,
                    ],
                ];
            });
        });
    }
}
