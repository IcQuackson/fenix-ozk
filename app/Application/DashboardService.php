<?php
namespace App\Application;

use App\Contracts\FenixPort;
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
		$cacheKey = "dashboard:summary:{$userId}:v1";

		// Instead of a plain remember(), wrap heavy recompute in a lock
		return cache()->remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
			$lock = cache()->lock("lock:dashboard:{$userId}", 10);

			return $lock->block(5, function () use ($userId) {
				// <- this block only runs once, other requests wait for it
				$me = $this->fenix->me($userId);
				$grades = $this->fenix->listGrades($userId);
				$ects = collect($grades)->sum(fn($g) => (float) ($g['ects'] ?? 0));

				return ['me' => $me, 'ectsSum' => $ects];
			});
		});
	}
}
