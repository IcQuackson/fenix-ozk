<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class ClassEvent
{
	/** @param RoomRef[] $locations */
	public function __construct(
		public string $title,
		public DateRange $classPeriod,
		public Course $course,
		public array $locations,
	) {
	}

	public static function fromApi(array $raw): self
	{
		$locations = [];
		foreach ((array) ($raw['location'] ?? []) as $location) {
			if (is_array($location)) {
				$locations[] = RoomRef::fromApi($location);
			}
		}

		$course = Course::fromApi((array) ($raw['course'] ?? []));

		$title = (string) ($raw['title'] ?? '');
		if ($title === '' && $course->name !== '') {
			$title = $course->name;
		}

		return new self(
			$title,
			DateRange::fromApi((array) ($raw['classPeriod'] ?? [])),
			$course,
			$locations,
		);
	}
}
