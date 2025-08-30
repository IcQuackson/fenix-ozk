<?php
// app/ViewModels/CurriculumKpisVM.php
namespace App\ViewModels;

final class CurriculumKpisVM
{
	public function __construct(
		public ?string $degreeId,
		public ?string $degreeAcronym,
		public ?string $degreeName,
		public float $totalEcts,
		public ?float $avgGrade,
		public float $ectsThisTerm,
		public ?float $ectsPerYear,
	) {
	}

	/**
	 * @param array{
	 *   degree: array{id:?string,acronym:?string,name:?string},
	 *   kpis: array{totalEcts:float,avgGrade:?float,ectsThisTerm:float,ectsPerYear:?float}
	 * } $domain
	 */
	public static function fromDomain(array $domain): self
	{
		$degree = $domain['degree'] ?? [];
		$kpis = $domain['kpis'] ?? [];

		return new self(
			$degree['id'] ?? null,
			$degree['acronym'] ?? null,
			$degree['name'] ?? null,
			(float) ($kpis['totalEcts'] ?? 0.0),
			isset($kpis['avgGrade']) ? (float) $kpis['avgGrade'] : null,
			(float) ($kpis['ectsThisTerm'] ?? 0.0),
			isset($kpis['ectsPerYear']) ? (float) $kpis['ectsPerYear'] : null,
		);
	}

	public function toArray(): array
	{
		return [
			'degree' => [
				'id' => $this->degreeId,
				'acronym' => $this->degreeAcronym,
				'name' => $this->degreeName,
				'display' => trim(($this->degreeAcronym ? "{$this->degreeAcronym} — " : '') . ($this->degreeName ?? '')) ?: null,
			],
			'kpis' => [
				'totalEcts' => $this->totalEcts,
				'avgGrade' => $this->avgGrade,
				'ectsThisTerm' => $this->ectsThisTerm,
				'ectsPerYear' => $this->ectsPerYear,
				// formatted
				'display' => [
					'totalEcts' => number_format($this->totalEcts, 0),
					'avgGrade' => $this->avgGrade !== null ? number_format($this->avgGrade, 2) : '—',
					'ectsThisTerm' => number_format($this->ectsThisTerm, 0),
					'ectsPerYear' => $this->ectsPerYear !== null ? number_format($this->ectsPerYear, 2) : '—',
				],
			],
		];
	}
}
