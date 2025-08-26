<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\CourseEvaluation;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class PersonService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache
	) {
	}

	/** @return CourseEvaluation[] */
	public function upcomingEvaluations(int $userId): array
	{
		$key = "person:{$userId}:evaluations:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(5),
			fn() =>
			$this->fenix->getPersonEvaluations($userId)
		);

		$evaluations = array_map(fn($r) => CourseEvaluation::fromApi($r), $raw);

		// sort by date, then filter only future ones
		return collect($evaluations)
			->filter(fn(CourseEvaluation $e) => $e->examAt && $e->examAt->isFuture())
			->sortBy(fn(CourseEvaluation $e) => $e->examAt)
			->values()
			->all();
	}
}
