<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class RoomCapacity
{
	public function __construct(
		public ?int $examCapacity,
		public ?int $normalCapacity,
		public ?int $normal,
		public ?int $exam,
	) {
	}

	public static function fromApi(array $raw): self
	{
		$toInt = static fn($v): ?int => isset($v) ? (int) $v : null;

		return new self(
			$toInt($raw['examCapacity'] ?? null),
			$toInt($raw['normalCapacity'] ?? null),
			$toInt($raw['normal'] ?? null),
			$toInt($raw['exam'] ?? null),
		);
	}
}
