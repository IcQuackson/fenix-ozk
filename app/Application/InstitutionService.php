<?php
namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\{
	Course,
	CourseEvaluation,
	CourseGroup,
	CourseSchedule,
	CourseStudent
};

use App\Domain\Entities\AcademicTerm;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class InstitutionService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache
	) {
	}


	/**
	 * Get the current academic term string, e.g. "1º Semestre 2024/2025".
	 */
	public function currentAcademicTerm(): string
	{
		$key = "about:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(10),
			fn() => $this->fenix->getAbout()
		);

		return (string) ($raw['currentAcademicTerm'] ?? '');
	}
}
