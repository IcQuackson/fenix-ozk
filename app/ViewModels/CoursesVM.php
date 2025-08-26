<?php
namespace App\ViewModels;

use App\Domain\Entities\Course;

final class CoursesVM
{
	/** @param Course[] $courses */
	public function __construct(public array $courses)
	{
	}

	/** @param Course[] $courses */
	public static function fromDomain(array $courses): self
	{
		return new self($courses);
	}

	/** @return array<int,array{name:string,ects:float,is_heavy:bool}> */
	public function toArray(): array
	{
		return array_map(fn(Course $c) => [
			'name' => $c->name,
			'ects' => $c->ects,
			'is_heavy' => $c->isHeavy(),
		], $this->courses);
	}
}
