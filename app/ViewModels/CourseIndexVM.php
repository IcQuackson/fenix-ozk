<?php

namespace App\ViewModels;

use App\Domain\Entities\Course;

final class CourseIndexVM
{
	/** @param CourseIndexItemVM[] $courses */
	public function __construct(public array $courses)
	{
		foreach ($courses as $course) {
			if (!$course instanceof CourseIndexItemVM) {
				throw new \InvalidArgumentException(
					sprintf('Expected %s, got %s', CourseIndexItemVM::class, get_debug_type($course))
				);
			}
		}
	}

	/**
	 * @param Course[] $domain
	 */
	public static function fromDomain(array $domain): self
	{
		$items = array_map(fn(Course $course) => CourseIndexItemVM::fromDomain($course), $domain);

		return new self($items);
	}

	public function toArray(): array
	{
		return array_map(fn(CourseIndexItemVM $course) => $course->toArray(), $this->courses);
	}
}

final class CourseIndexItemVM
{
	public function __construct(
		public string $id,
		public string $name,
		public float $ects,
	) {
	}

	public static function fromDomain(Course $course): self
	{
		$ects = $course->ects ?? 0.0;

		return new self(
			$course->id,
			$course->name,
			(float) $ects
		);
	}

	public function isHeavy(): bool
	{
		return $this->ects >= 6.0;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'ects' => $this->ects,
			'heavy' => $this->isHeavy(),
		];
	}
}
