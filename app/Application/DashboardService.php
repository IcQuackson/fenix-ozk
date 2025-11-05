<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\Course;
use App\Domain\Entities\CourseEvaluation;
use App\Domain\Entities\Curriculum;
use App\Domain\Entities\Person;
use App\Domain\Entities\EvaluationType;
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
                $curriculum = $this->personService->getLatestCurriculum($userId);

                return $curriculum ? (float) $curriculum->credits : 0.0;
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
     *     financialStanding: array{
     *       pendingCount:int,
     *       pendingTotal:?float,
     *       nextDueDate:?string
     *     },
     *     nextProject:?array{
     *       evaluationName:string,
     *       courseName:?string,
     *       dueAt:?string,
     *       link:?string
     *     }
     *   }
     * }
     */
    public function curriculumKpis(int $userId): array
    {
        $cacheKey = "dashboard:curriculumKpis:{$userId}:v2";

        return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $lock = $this->cache->lock("lock:dashboardKpis:{$userId}", 10);

            return $lock->block(5, function () use ($userId) {
                $curriculum = $this->personService->getLatestCurriculum($userId);
                $financialStanding = $this->buildFinancialStanding($userId);
                $nextProject = $this->determineNextProject($userId);

                if (! $curriculum) {
                    return [
                        'degree' => ['id' => null, 'acronym' => null, 'name' => null],
                        'kpis' => [
                            'totalEcts' => 0.0,
                            'avgGrade' => null,
                            'financialStanding' => $financialStanding,
                            'nextProject' => $nextProject,
                        ],
                    ];
                }

                // 1) Total earned ECTS
                $totalEcts = (float) $curriculum->credits;

                // 2) Average grade (prefer calculatedAverage)
                $avgGrade = $curriculum->average ?: null;
                $avgGrade = $avgGrade !== null ? (float) $avgGrade : null;

                return [
                    'degree' => [
                        'id' => $curriculum->degreeId,
                        'acronym' => $curriculum->degreeAcronym,
                        'name' => $curriculum->degreeName,
                    ],
                    'kpis' => [
                        'totalEcts' => round($totalEcts, 2),
                        'avgGrade' => $avgGrade !== null ? round($avgGrade, 2) : null,
                        'financialStanding' => $financialStanding,
                        'nextProject' => $nextProject,
                    ],
                ];
            });
        });
    }

    /**
     * @return array{pendingCount:int,pendingTotal:?float,nextDueDate:?string}
     */
    private function buildFinancialStanding(int $userId): array
    {
        $pendingCount = 0;
        $pendingTotal = null;
        $nextDueDate = null;

        $payments = $this->fenix->getPersonPayments($userId);
        $pending = (array) ($payments['pending'] ?? []);

        foreach ($pending as $payment) {
            if (!is_array($payment)) {
                continue;
            }

            $pendingCount++;

            $amount = $this->normalizeAmount($payment['amount'] ?? null);
            if ($amount !== null) {
                $pendingTotal = ($pendingTotal ?? 0.0) + $amount;
            }

            $period = $payment['paymentPeriod'] ?? [];
            $dueRaw = is_array($period) ? ($period['end'] ?? $period['start'] ?? null) : null;
            $due = $this->parseDate($dueRaw);

            if ($due !== null && ($nextDueDate === null || $due->lessThan($nextDueDate))) {
                $nextDueDate = $due;
            }
        }

        return [
            'pendingCount' => $pendingCount,
            'pendingTotal' => $pendingTotal !== null ? round($pendingTotal, 2) : null,
            'nextDueDate' => $nextDueDate?->toAtomString(),
        ];
    }

    /**
     * @return array{
     *   evaluationName:string,
     *   courseName:?string,
     *   dueAt:?string,
     *   link:?string
     * }|null
     */
    private function determineNextProject(int $userId): ?array
    {
        $projects = collect($this->getUpcomingEvaluations($userId))
            ->filter(function (CourseEvaluation $evaluation) {
                return $evaluation->type === EvaluationType::PROJECT;
            })
            ->map(function (CourseEvaluation $evaluation) {
                $due = $evaluation->evaluationPeriod?->end ?? $evaluation->evaluationPeriod?->start;

                return [
                    'evaluation' => $evaluation,
                    'dueAt' => $due,
                ];
            })
            ->filter(fn ($item) => $item['dueAt'] instanceof \Carbon\CarbonInterface)
            ->sortBy(fn ($item) => $item['dueAt']->getTimestamp())
            ->values();

        if ($projects->isEmpty()) {
            return null;
        }

        /** @var array{evaluation:CourseEvaluation,dueAt:\Carbon\CarbonInterface} $next */
        $next = $projects->first();
        $evaluation = $next['evaluation'];
        $dueAt = $next['dueAt'];

        $course = $evaluation->course;
        $link = $course?->summaryLink
            ?? $course?->announcementLink
            ?? $course?->url;

        return [
            'evaluationName' => $evaluation->name,
            'courseName' => $course?->name,
            'dueAt' => $dueAt?->toAtomString(),
            'link' => $link,
        ];
    }

    private function normalizeAmount(mixed $amount): ?float
    {
        if (is_int($amount) || is_float($amount)) {
            return (float) $amount;
        }

        if (!is_string($amount)) {
            return null;
        }

        $clean = trim($amount);
        if ($clean === '') {
            return null;
        }

        $clean = preg_replace('/[^0-9,.\-]/', '', $clean) ?? '';
        if ($clean === '') {
            return null;
        }

        $hasComma = str_contains($clean, ',');
        $hasDot = str_contains($clean, '.');

        if ($hasComma && $hasDot) {
            $lastComma = strrpos($clean, ',');
            $lastDot = strrpos($clean, '.');
            if ($lastComma !== false && $lastDot !== false) {
                if ($lastComma > $lastDot) {
                    $clean = str_replace('.', '', $clean);
                    $clean = str_replace(',', '.', $clean);
                } else {
                    $clean = str_replace(',', '', $clean);
                }
            }
        } elseif ($hasComma) {
            $clean = str_replace(',', '.', $clean);
        }

        if (!is_numeric($clean)) {
            return null;
        }

        return (float) $clean;
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance(\DateTime::createFromInterface($value));
        }

        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value) === 1) {
                return Carbon::createFromFormat('d/m/Y', $value)->endOfDay();
            }

            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
