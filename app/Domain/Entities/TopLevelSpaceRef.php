<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class TopLevelSpaceRef
{
	public function __construct(
		public TopLevelSpaceType $type,
		public string $id,
		public string $name,
	) {
	}

	public static function fromApi(array $raw): self
	{
		return new self(
			TopLevelSpaceType::tryFrom((string) ($raw['type'] ?? 'CAMPUS')) ?? TopLevelSpaceType::CAMPUS,
			(string) ($raw['id'] ?? ''),
			(string) ($raw['name'] ?? ''),
		);
	}
}
