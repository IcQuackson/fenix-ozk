<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\ClassEvent;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class ClassScheduleService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache,
	) {
	}

	/** @return ClassEvent[] */
	public function getNextClasses(int $userId, int $limit = 2): array
	{
		$limit = max(1, $limit);
		$cacheKey = "person:{$userId}:nextClasses:{$limit}:v1";

		return $this->cache->remember(
			$cacheKey,
			now()->addMinutes(1),
			function () use ($userId, $limit) {
				$raw = $this->fenix->getPersonCalendarClasses($userId);

				if (!is_array($raw)) {
					return [];
				}

				$rawEvents = $raw['events'] ?? [];
				if (!is_array($rawEvents)) {
					$rawEvents = [];
				}
				$rawEvents = array_values(array_filter($rawEvents, 'is_array'));

				$events = array_map(
					fn(array $event) => ClassEvent::fromApi($event),
					$rawEvents
				);

				$sorted = collect($events)
					->filter(fn(ClassEvent $event) => $event->classPeriod->start !== null)
					->sortBy(fn(ClassEvent $event) => $event->classPeriod->start?->getTimestamp() ?? PHP_INT_MAX)
					->values();

				if ($sorted->isEmpty()) {
					return [];
				}

				$now = CarbonImmutable::now();

				$index = $sorted->search(function (ClassEvent $event) use ($now) {
					$start = $event->classPeriod->start;
					$end = $event->classPeriod->end;

					if ($start === null) {
						return false;
					}

					$started = $start->lessThanOrEqualTo($now);
					$notFinished = $end === null || $end->greaterThanOrEqualTo($now);

					return $started && $notFinished;
				});

				if ($index === false) {
					$index = $sorted->search(function (ClassEvent $event) use ($now) {
						$start = $event->classPeriod->start;
						return $start !== null && $start->greaterThanOrEqualTo($now);
					});
				}

				if ($index === false) {
					return [];
				}

				return $sorted
					->slice((int) $index, $limit)
					->values()
					->all();
			}
		);
	}
}
