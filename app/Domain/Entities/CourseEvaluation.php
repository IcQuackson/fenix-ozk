<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class CourseEvaluation
{
	/** @param RoomRef[] $rooms */
	public function __construct(
		public ?string $id,
		public ?EvaluationType $type,
		public string $name,
		public ?DateRange $evaluationPeriod,
		public ?DateRange $enrollmentPeriod,
		public ?bool $isInEnrolmentPeriod,
		public ?bool $isEnrolled,
		public array $rooms,
		public ?RoomRef $assignedRoom,
		public Course $course,
	) {
	}

	public static function fromApi(array $raw): self
	{
		$rooms = [];
		foreach ((array) ($raw['rooms'] ?? []) as $r) {
			if (is_array($r)) {
				$rooms[] = RoomRef::fromApi($r);
			}
		}

		return new self(
			isset($raw['id']) ? (string) $raw['id'] : null,
			isset($raw['type']) && $raw['type'] !== null
			? EvaluationType::tryFrom((string) $raw['type'])
			: null,
			(string) ($raw['name'] ?? ''),
			DateRange::fromApi((array) ($raw['evaluationPeriod'] ?? [])),
			isset($raw['enrollmentPeriod']) && is_array($raw['enrollmentPeriod'])
			? DateRange::fromApi($raw['enrollmentPeriod'])
			: null,
			array_key_exists('isInEnrolmentPeriod', $raw) ? (bool) $raw['isInEnrolmentPeriod'] : null,
			array_key_exists('isEnrolled', $raw) ? (bool) $raw['isEnrolled'] : null,
			$rooms,
			isset($raw['assignedRoom']) && is_array($raw['assignedRoom'])
			? RoomRef::fromApi($raw['assignedRoom'])
			: null,
			isset($raw['courses'][0])
			? Course::fromApi($raw['courses'][0])
			: null,
		);
	}
}
