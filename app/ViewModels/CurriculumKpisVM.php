<?php
// app/ViewModels/CurriculumKpisVM.php
namespace App\ViewModels;

use Carbon\Carbon;

final class CurriculumKpisVM
{
	public function __construct(
		public ?string $degreeId,
		public ?string $degreeAcronym,
		public ?string $degreeName,
		public float $totalEcts,
		public ?float $avgGrade,
		/** @var array{pendingCount:int,pendingTotal:?float,nextDueDate:?string} */
		public array $financialStanding,
		/** @var array{evaluationName:string,courseName:?string,dueAt:?string,link:?string}|null */
		public ?array $nextProject,
	) {
	}

	/**
	 * @param array{
	 *   degree: array{id:?string,acronym:?string,name:?string},
	 *   kpis: array{
	 *     totalEcts:float,
	 *     avgGrade:?float,
	 *     financialStanding:array{pendingCount:int,pendingTotal:?float,nextDueDate:?string},
	 *     nextProject:?array{evaluationName:string,courseName:?string,dueAt:?string,link:?string}
	 *   }
	 * } $domain
	 */
	public static function fromDomain(array $domain): self
	{
		$degree = $domain['degree'] ?? [];
		$kpis = $domain['kpis'] ?? [];
		$financial = is_array($kpis['financialStanding'] ?? null)
			? $kpis['financialStanding']
			: [];
		$nextProject = is_array($kpis['nextProject'] ?? null) ? $kpis['nextProject'] : null;

		return new self(
			$degree['id'] ?? null,
			$degree['acronym'] ?? null,
			$degree['name'] ?? null,
			(float) ($kpis['totalEcts'] ?? 0.0),
			isset($kpis['avgGrade']) ? (float) $kpis['avgGrade'] : null,
			[
				'pendingCount' => (int) ($financial['pendingCount'] ?? 0),
				'pendingTotal' => isset($financial['pendingTotal']) ? (float) $financial['pendingTotal'] : null,
				'nextDueDate' => $financial['nextDueDate'] ?? null,
			],
			$nextProject
				? [
					'evaluationName' => (string) ($nextProject['evaluationName'] ?? ''),
					'courseName' => $nextProject['courseName'] ?? null,
					'dueAt' => $nextProject['dueAt'] ?? null,
					'link' => $nextProject['link'] ?? null,
				]
				: null,
		);
	}

	public function toArray(): array
	{
		$financialDisplay = $this->formatFinancialStanding();
		$nextProjectDisplay = $this->formatNextProject();

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
				'financialStanding' => $this->financialStanding,
				'nextProject' => $this->nextProject,
				// formatted
				'display' => [
					'totalEcts' => number_format($this->totalEcts, 0),
					'avgGrade' => $this->avgGrade !== null ? number_format($this->avgGrade, 2) : '—',
					'financialStanding' => $financialDisplay,
					'nextProject' => $nextProjectDisplay,
				],
			],
		];
	}

	/**
	 * @return array{value:string,subtitle:string,subtitleClass:string,link:?string}
	 */
	private function formatFinancialStanding(): array
	{
		$pendingCount = $this->financialStanding['pendingCount'] ?? 0;
		$pendingTotal = $this->financialStanding['pendingTotal'] ?? null;
		$nextDueIso = $this->financialStanding['nextDueDate'] ?? null;
		$nextDue = $this->formatDate($nextDueIso, 'd/m');

		if ($pendingCount <= 0) {
			return [
				'value' => 'Pagamentos regularizados',
				'link' => null,
			];
		}

		$value = $pendingCount === 1 ? '1 pendência' : sprintf('%d pendências', $pendingCount);

		$subtitleParts = [];
		if ($pendingTotal !== null) {
			$subtitleParts[] = sprintf('Total em dívida: €%s', number_format($pendingTotal, 2, ',', ' '));
		}
		if ($nextDue) {
			$subtitleParts[] = sprintf('Próximo pagamento: %s', $nextDue);
		}
		$subtitle = implode(' · ', $subtitleParts);
		if ($subtitle === '') {
			$subtitle = 'Pagamentos por regularizar';
		}

		return [
			'value' => $value,
			'subtitle' => $subtitle,
			'subtitleClass' => 'text-amber-400',
			'link' => null,
		];
	}

	/**
	 * @return array{value:string,subtitle:string,subtitleClass:string,link:?string}
	 */
	private function formatNextProject(): array
	{
		if ($this->nextProject === null) {
			return [
				'value' => 'Sem projetos',
				'subtitle' => '',
				'subtitleClass' => 'text-slate-500',
				'link' => null,
			];
		}

		$due = $this->formatDate($this->nextProject['dueAt'] ?? null, 'd/m');
		$value = $due ?? 'Data por definir';

		$course = $this->nextProject['courseName'] ?? null;
		$evaluation = trim($this->nextProject['evaluationName'] ?? '');

		$subtitle = $course
			? trim("{$evaluation} — {$course}")
			: $evaluation;

		return [
			'value' => $value,
			'subtitle' => $subtitle,
			'subtitleClass' => 'text-slate-500',
			'link' => $this->nextProject['link'] ?? null,
		];
	}

	private function formatDate(?string $iso, string $format): ?string
	{
		if (!$iso) {
			return null;
		}

		try {
			$timezone = config('app.timezone', 'UTC');
			return Carbon::parse($iso)->setTimezone($timezone)->format($format);
		} catch (\Throwable) {
			return null;
		}
	}
}
