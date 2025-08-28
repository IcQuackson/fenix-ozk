<?php
namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\CourseEvaluation;
use App\Domain\Entities\Course;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class DashboardService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache,
		private PersonService $person,
		private InstitutionService $institution,
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

				$term = $this->institution->currentAcademicTerm();
				$courses = $this->person->getEnrolledCoursesByTerm($userId, $term);


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

				$courses = $this->person->getCurrentEnrolledCourses($userId);

				return $courses;
			});
		});
	}


}
