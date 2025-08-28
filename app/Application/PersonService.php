<?php

namespace App\Application;

use App\Contracts\FenixPort;
use App\Domain\Entities\CourseEvaluation;
use App\Domain\Entities\Course;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Log;

final class PersonService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache,
		private InstitutionService $institutionService
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

	public function getEnrolledCoursesByTerm(int $userId, string $term): array
	{
		$raw = $this->fenix->getPersonCourses($userId, $term);

		// Log the inputs
		Log::info('Fetching enrolled courses', [
			'userId' => $userId,
			'term' => $term,
		]);

		// Log the raw API response (array pretty-printed as JSON)
		Log::debug('Fenix API response', [
			'raw' => $raw,
		]);

		$courses = array_map(fn($c) => Course::fromApi($c), $raw['enrolments'] ?? []);

		return $courses;
	}

	public function getCurrentEnrolledCourses(int $userId): array
	{
		$term = $this->institutionService->currentAcademicTerm();
		return $this->getEnrolledCoursesByTerm($userId, $term);
	}
}
