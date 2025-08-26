<?php
namespace App\Domain\Entities;

final class Course
{
    public function __construct(
        public string $id,
        public string $acronym,
        public string $name,
        public string $academicTerm,
        public ?float $ects,
    ) {
    }

    public static function fromApi(array $raw): self
    {
        return new self(
            $raw['id'] ?? '',
            $raw['acronym'] ?? '',
            $raw['name'] ?? '',
            $raw['academicTerm'] ?? '',
            isset($raw['ects']) ? (float) $raw['ects'] : null,
        );
    }

    public function isHeavy(): bool
    {
        return ($this->ects ?? 0) >= 6.0;
    }
}
