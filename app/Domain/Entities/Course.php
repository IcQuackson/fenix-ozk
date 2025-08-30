<?php
// app/Domain/Entities/Course.php
namespace App\Domain\Entities;

final class Course
{
    public function __construct(
        public string $id,
        public string $acronym,
        public string $name,
        public string $academicTerm,
        public ?string $url,
        public ?string $announcementLink,
        // Optional, present in enrolments/curriculum items
        public ?string $grade = null,
        public ?float $ects = null,
        // Optional, present in full course details
        public ?string $summaryLink = null,
        public ?string $evaluationMethod = null,
        public ?int $numberOfAttendingStudents = null,
    ) {
    }

    /** Accepts both “ref” and full course payloads. */
    public static function fromApi(array $raw): self
    {
        return new self(
            (string) ($raw['id'] ?? ''),
            (string) ($raw['acronym'] ?? ''),
            (string) ($raw['name'] ?? ''),
            (string) ($raw['academicTerm'] ?? ''),
            url: $raw['url'] ?? null,
            announcementLink: $raw['announcementLink'] ?? null,
            grade: $raw['grade'] ?? null,          // enrolments / curriculum
            ects: isset($raw['ects']) ? (float) $raw['ects'] : null, // curriculum
            summaryLink: $raw['summaryLink'] ?? null,               // full course
            evaluationMethod: $raw['evaluationMethod'] ?? null,     // full course
            numberOfAttendingStudents: isset($raw['numberOfAttendingStudents'])
            ? (int) $raw['numberOfAttendingStudents']
            : null,
        );
    }
}
