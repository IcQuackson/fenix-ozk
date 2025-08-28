<?php
namespace App\ViewModels;

use App\Domain\Entities\Course;

final class CurrentEnrolledCoursesVM
{
	/** @var Course[] */
	public array $courses;

	/**
	 * @param Course[] $courses
	 */
	public function __construct(array $courses)
	{
		foreach ($courses as $c) {
			if (!$c instanceof Course) {
				throw new \InvalidArgumentException(
					sprintf('Expected instance of %s, got %s', Course::class, get_debug_type($c))
				);
			}
		}

		$this->courses = $courses;
	}

	public static function fromDomain(array $courses): self
	{
		return new self($courses);
	}

	public function toArray(): array
	{
		return array_map(fn(Course $c) => [
			'id' => $c->id,
			'acronym' => $c->acronym,
			'name' => $c->name,
			'term' => $c->academicTerm,
			'url' => $c->url,
		], $this->courses);
	}
}
