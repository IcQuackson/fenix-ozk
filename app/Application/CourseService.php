<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\Course;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Carbon;

final class CourseService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache
	) {
	}

	/** @return Course[] */
	public function listUserCourses(int $userId): array
	{
		$key = "courses:{$userId}:v1";
		$lockKey = "lock:courses:{$userId}";
		$ttl = Carbon::now()->addMinutes(5);

		// 1) Fast path
		if ($cached = $this->cache->get($key)) {
			/** @var Course[] $cached */
			return $cached;
		}

		// 2) Slow path with stampede protection
		$lock = $this->cache->lock($lockKey, 10); // lock expires after 10s
		return $lock->block(5, function () use ($key, $ttl, $userId) {
			// Re-check inside the lock (another request may have filled it)
			if ($cached = $this->cache->get($key)) {
				return $cached;
			}

			// Heavy recompute / remote calls
			$raw = $this->fenix->listCourses($userId);
			$courses = array_map(fn($r) => Course::fromArray($r), $raw);

			// Save & return
			$this->cache->put($key, $courses, $ttl);
			return $courses;
		});
	}
}
