<?php
namespace App\Domain\Entities;

final class CourseSchedule
{
    public function __construct(
        public string $weekday,
        public string $start,
        public string $end,
        public string $room,
    ) {}

    public static function fromApi(array $raw): self
    {
        return new self(
            $raw['weekday'] ?? '',
            $raw['start'] ?? '',
            $raw['end'] ?? '',
            $raw['room'] ?? '',
        );
    }
}
