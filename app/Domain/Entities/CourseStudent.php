<?php
namespace App\Domain\Entities;

final class CourseStudent
{
	public function __construct(
		public string $username,
		public string $name,
	) {
	}

	public static function fromApi(array $raw): self
	{
		return new self(
			$raw['username'] ?? '',
			$raw['name'] ?? '',
		);
	}
}
