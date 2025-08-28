<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class AcademicTerm
{
	public function __construct(
		public string $year,   // e.g. "2024/2025"
		public string $label,  // e.g. "1º Semestre 2024/2025"
	) {
	}

	public static function fromApi(string $year, string $label): self
	{
		return new self($year, $label);
	}

	/**
	 * @param array<string, string[]> $raw
	 * @return self[]
	 */
	public static function listFromApi(array $raw): array
	{
		$terms = [];
		foreach ($raw as $year => $labels) {
			foreach ((array) $labels as $label) {
				if (is_string($label)) {
					$terms[] = self::fromApi((string) $year, $label);
				}
			}
		}
		return $terms;
	}

	public function getSemesterNumber(): ?int
	{
		if (preg_match('/(^|\D)([12])\s*(?:º|o|st|nd)?\s*(?:Sem|Semestre|Semester)/i', $this->label, $m)) {
			return (int) $m[2];
		}
		return null;
	}

	public function isFirstSemester(): bool
	{
		return $this->getSemesterNumber() === 1;
	}

	public function isSecondSemester(): bool
	{
		return $this->getSemesterNumber() === 2;
	}

	public function getStartYear(): int
	{
		[$y1] = explode('/', $this->year);
		return (int) $y1;
	}

	public function getEndYear(): int
	{
		[, $y2] = explode('/', $this->year);
		return (int) $y2;
	}
}
