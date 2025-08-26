<?php
namespace App\Domain\Entities;

final class CourseGroup
{
    public function __construct(
        public string $id,
        public string $shift,
        public array $students,
    ) {}

    public static function fromApi(array $raw): self
    {
        return new self(
            $raw['id'] ?? '',
            $raw['shift'] ?? '',
            $raw['students'] ?? [],
        );
    }
}
