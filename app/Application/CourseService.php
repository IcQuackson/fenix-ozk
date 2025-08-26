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
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class CourseService
{
	public function __construct(
		private FenixPort $fenix,
		private CacheRepository $cache
	) {
	}

	public function getCourse(string $id): Course
	{
		$key = "course:{$id}:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(10),
			fn() =>
			$this->fenix->getCourseById($id)
		);
		return Course::fromApi($raw);
	}

	/** @return CourseEvaluation[] */
	public function listEvaluations(string $id): array
	{
		$key = "course:{$id}:evaluations:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(10),
			fn() =>
			$this->fenix->listCourseEvaluations($id)
		);
		return array_map(fn($r) => CourseEvaluation::fromApi($r), $raw);
	}

	/** @return CourseGroup[] */
	public function listGroups(string $id): array
	{
		$key = "course:{$id}:groups:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(10),
			fn() =>
			$this->fenix->listCourseGroups($id)
		);
		return array_map(fn($r) => CourseGroup::fromApi($r), $raw);
	}

	/** @return CourseSchedule[] */
	public function getSchedule(string $id): array
	{
		$key = "course:{$id}:schedule:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(10),
			fn() =>
			$this->fenix->getCourseSchedule($id)
		);
		return array_map(fn($r) => CourseSchedule::fromApi($r), $raw);
	}

	/** @return CourseStudent[] */
	public function listStudents(string $id): array
	{
		$key = "course:{$id}:students:v1";
		$raw = $this->cache->remember(
			$key,
			now()->addMinutes(10),
			fn() =>
			$this->fenix->listCourseStudents($id)
		);
		return array_map(fn($r) => CourseStudent::fromApi($r), $raw);
	}
}
