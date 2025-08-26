<?php
namespace App\ViewModels;

use App\Domain\Entities\Course;

final class CourseVM
{
	public function __construct(public Course $course)
	{
	}

	public static function fromDomain(Course $c): self
	{
		return new self($c);
	}

	public function toArray(): array
	{
		return [
			'id' => $this->course->id,
			'acronym' => $this->course->acronym,
			'name' => $this->course->name,
			'term' => $this->course->academicTerm,
			'url' => $this->course->url,
		];
	}
}
