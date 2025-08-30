<?php
// app/Domain/Entities/Curriculum.php
namespace App\Domain\Entities;

use DateTimeImmutable;

final class Curriculum
{
	/** @param Course[] $approvedCourses */
	public function __construct(
		// Inlined DegreeRef (no Program entity)
		public string $degreeId,
		public string $degreeAcronym,
		public string $degreeName,

		public ?DateTimeImmutable $start,
		public ?DateTimeImmutable $end,

		public float $credits,
		public float $average,
		public float $calculatedAverage,
		public bool $isFinished,
		public int $numberOfApprovedCourses,
		public int $currentYear,

		public array $approvedCourses,
	) {
	}

	public static function fromApi(?array $raw): self
	{
		$raw ??= [];

		// Map approvedCourses[] by flattening {course:{...}, grade, ects} into Course::fromApi(...)
		$approvedCourses = [];
		foreach (is_array($raw['approvedCourses'] ?? null) ? $raw['approvedCourses'] : [] as $item) {
			$courseRaw = ($item['course'] ?? []) + [
				'grade' => $item['grade'] ?? null,
				'ects' => $item['ects'] ?? null,
			];
			$approvedCourses[] = Course::fromApi($courseRaw);
		}

		$degree = is_array($raw['degree'] ?? null) ? $raw['degree'] : [];

		return new self(
			(string) ($degree['id'] ?? ''),
			(string) ($degree['acronym'] ?? ''),
			(string) ($degree['name'] ?? ''),

			self::parseDate($raw['start'] ?? null),
			self::parseDate($raw['end'] ?? null),

			(float) ($raw['credits'] ?? 0.0),
			(float) ($raw['average'] ?? 0.0),
			(float) ($raw['calculatedAverage'] ?? 0.0),
			(bool) ($raw['isFinished'] ?? false),
			(int) ($raw['numberOfApprovedCourses'] ?? count($approvedCourses)),
			(int) ($raw['currentYear'] ?? 0),

			$approvedCourses
		);
	}

	private static function parseDate(?string $value): ?DateTimeImmutable
	{
		if (!$value)
			return null;
		try {
			return new DateTimeImmutable($value);
		} catch (\Throwable) {
			return null;
		}
	}
}
