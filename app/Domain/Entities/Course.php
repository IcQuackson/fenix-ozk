<?php
namespace App\Domain\Entities;

final class Course
{
    public function __construct(
        public string $id,
        public string $name,
        public float $ects,
    ) {}

    public static function fromArray(array $r): self
    {
        return new self(
            (string)($r['id'] ?? $r['courseId'] ?? ''),
            (string)($r['acronym'] ?? $r['name'] ?? 'N/A'),
            (float)($r['ects'] ?? 0),
        );
    }

    public function isHeavy(): bool { return $this->ects >= 6.0; }
}
