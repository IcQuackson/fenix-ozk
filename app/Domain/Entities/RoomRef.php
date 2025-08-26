<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class RoomRef
{
	public function __construct(
		public RoomRefType $type,
		public string $id,
		public string $name,
		public TopLevelSpaceRef $topLevelSpace,
		public ?string $description,
		public ?RoomCapacity $capacity,
	) {
	}

	public static function fromApi(array $raw): self
	{
		return new self(
			RoomRefType::tryFrom((string) ($raw['type'] ?? 'ROOM')) ?? RoomRefType::ROOM,
			(string) ($raw['id'] ?? ''),
			(string) ($raw['name'] ?? ''),
			TopLevelSpaceRef::fromApi((array) ($raw['topLevelSpace'] ?? [])),
			isset($raw['description']) ? (string) $raw['description'] : null,
			isset($raw['capacity']) && is_array($raw['capacity'])
			? RoomCapacity::fromApi($raw['capacity'])
			: null,
		);
	}
}
