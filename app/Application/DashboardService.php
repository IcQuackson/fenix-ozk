<?php
namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\CourseEvaluation;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class DashboardService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache
	) {
	}

	/** @return array<string,mixed> */
	public function summaryForUser(int $userId): array
	{
		// bump since "evaluations" shape changed (examAt->evaluationPeriod.start, etc.)
		$cacheKey = "dashboard:summary:{$userId}:v2";

		return $this->cache->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
			$lock = $this->cache->lock("lock:dashboard:{$userId}", 10);

			return $lock->block(5, function () use ($userId) {
				// --- base info
				$me = $this->fenix->getPerson($userId);

				// curriculum has courses with ECTS and grades
				$curriculum = $this->fenix->getPersonCurriculum($userId);
				$ects = collect($curriculum)->sum(fn($c) => (float) ($c['ects'] ?? 0));

				// --- next evaluations (spec-aligned CourseEvaluation)
				$rawEvals = $this->fenix->getPersonEvaluations($userId);

				/** @var CourseEvaluation[] $evaluations */
				$evaluations = [];
				foreach ((array) $rawEvals as $r) {
					$evaluations[] = CourseEvaluation::fromApi($r);

				}

				$nextEvals = collect($evaluations)
					->filter(fn(CourseEvaluation $e) => $e->evaluationPeriod->start->isFuture())
					->sortBy(fn(CourseEvaluation $e) => $e->evaluationPeriod->start->getTimestamp())
					->map(fn(CourseEvaluation $e) => [
						'name' => $e->name,
						'type' => $e->type?->value, // TEST|EXAM|...
						'exam_at' => $e->evaluationPeriod->start->format('Y-m-d H:i'),
						'room' => $e->assignedRoom?->name, // optional, useful on the dashboard
					])
					->values()
					->all();

				return [
					'me' => $me,
					'ectsSum' => $ects,
					'evaluations' => $nextEvals,
				];
			});
		});
	}

}
