<?php
// app/Domain/Entities/CurriculumCollection.php
namespace App\Domain\Entities;

use DateTimeImmutable;

final class CurriculumCollection implements \IteratorAggregate, \Countable
{
	/** @param Curriculum[] $items */
	public function __construct(private array $items)
	{
	}

	/** @return Curriculum[] */
	public function all(): array
	{
		return $this->items;
	}

	public function getIterator(): \Traversable
	{
		yield from $this->items;
	}

	public function count(): int
	{
		return \count($this->items);
	}

	public static function fromApi(?array $rawList): self
	{
		$rawList = is_array($rawList) ? $rawList : [];
		$items = array_map(fn($r) => Curriculum::fromApi($r), $rawList);
		return new self($items);
	}

	/**
	 * "Last available" strategy:
	 * 1) Prefer ongoing curriculum (end === null).
	 * 2) Else the one with the most recent 'end'.
	 * 3) Else the one with the most recent 'start'.
	 */
	public function latest(): ?Curriculum
	{
		if (!$this->items)
			return null;

		$ongoing = array_values(array_filter($this->items, fn($c) => $c->end === null));
		if ($ongoing) {
			usort($ongoing, fn($a, $b) => self::cmpDateDesc($a->start, $b->start));
			return $ongoing[0];
		}

		$byEnd = array_values(array_filter($this->items, fn($c) => $c->end instanceof DateTimeImmutable));
		if ($byEnd) {
			usort($byEnd, fn($a, $b) => self::cmpDateDesc($a->end, $b->end));
			return $byEnd[0];
		}

		$byStart = $this->items;
		usort($byStart, fn($a, $b) => self::cmpDateDesc($a->start, $b->start));
		return $byStart[0] ?? null;
	}

	private static function cmpDateDesc(?DateTimeImmutable $a, ?DateTimeImmutable $b): int
	{
		if ($a === null && $b === null)
			return 0;
		if ($a === null)
			return 1;
		if ($b === null)
			return -1;
		return $b <=> $a;
	}
}
