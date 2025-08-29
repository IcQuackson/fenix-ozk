<?php
namespace App\Domain\Entities;

final class Course
{
    public function __construct(
        public string $id,
        public string $acronym,
        public string $name,
        public string $academicTerm,
        public string $url,
        public string $announcementLink
    ) {
    }

    public static function fromApi(array $raw): self
    {
        return new self(
            $raw['id'] ?? '',
            $raw['acronym'] ?? '',
            $raw['name'] ?? '',
            $raw['academicTerm'] ?? '',
            $raw['url'] ?? '',
            announcementLink: $raw['announcementLink'] ?? '',
        );
    }
}
