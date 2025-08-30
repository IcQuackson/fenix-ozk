<?php
namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\CourseEvaluation;
use App\Domain\Entities\Course;
use App\Domain\Entities\Curriculum;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Carbon\Carbon;

final class DashboardService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache,
		private PersonService $personService,
		private InstitutionService $institutionService
	) {
	}

	/**
	 * @return array{me:array<string,mixed>,ectsSum:float,evaluations:CourseEvaluation[]}
	 */
	public function summaryForUser(int $userId): array
	{
		$cacheKey = "dashboard:summary:{$userId}:v2";

		return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
			$lock = $this->cache->lock("lock:dashboard:{$userId}", 10);

			return $lock->block(5, function () use ($userId) {
				$me = $this->fenix->getPerson($userId);
				$curriculum = $this->fenix->getPersonCurriculum($userId);
				$ects = collect($curriculum)->sum(fn($c) => (float) ($c['ects'] ?? 0));

				$rawEvals = $this->fenix->getPersonEvaluations($userId);

				/** @var CourseEvaluation[] $evaluations */
				$evaluations = [];
				foreach ((array) $rawEvals as $r) {
					$evaluations[] = CourseEvaluation::fromApi($r);
				}

				// future only, sorted
				$evaluations = collect($evaluations)
					->filter(fn(CourseEvaluation $e) => $e->evaluationPeriod->start->isFuture())
					->sortBy(fn(CourseEvaluation $e) => $e->evaluationPeriod->start->getTimestamp())
					->values()
					->all();

				$term = $this->institutionService->currentAcademicTerm();
				$courses = $this->personService->getEnrolledCoursesByTerm($userId, $term);


				return [
					'me' => $me,
					'ectsSum' => $ects,
					'evaluations' => $evaluations,
				];
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

	/** @return ?Curriculum */
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

				if (!$curriculum) {
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
