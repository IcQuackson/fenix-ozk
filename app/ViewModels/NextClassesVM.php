<?php

namespace App\ViewModels;

use App\Domain\Entities\ClassEvent;
use App\Domain\Entities\RoomRef;
use Carbon\CarbonImmutable;

final class NextClassesVM
{
	/** @param ClassEvent[] $classes */
	public function __construct(private array $classes)
	{
	}

	public static function fromDomain(array $classes): self
	{
		return new self($classes);
	}

	/** @return array<int,array<string,mixed>> */
	public function toArray(): array
	{
		$now = CarbonImmutable::now();

		return array_map(function (ClassEvent $event) use ($now) {
			$start = $event->classPeriod->start;
			$end = $event->classPeriod->end;

			$isOngoing = false;
			if ($start !== null) {
				$hasStarted = $start->lessThanOrEqualTo($now);
				$notFinished = $end === null || $end->greaterThanOrEqualTo($now);
				$isOngoing = $hasStarted && $notFinished;
			}

			return [
				'title' => $event->title,
				'course' => [
					'id' => $event->course->id,
					'acronym' => $event->course->acronym,
					'name' => $event->course->name,
					'academicTerm' => $event->course->academicTerm,
				],
				'period' => [
					'start' => $start?->toIso8601String(),
					'end' => $end?->toIso8601String(),
				],
				'rooms' => array_map(
					fn (RoomRef $room) => [
						'id' => $room->id,
						'name' => $room->name,
						'type' => $room->type->value,
						'topLevelSpace' => [
							'id' => $room->topLevelSpace->id,
							'name' => $room->topLevelSpace->name,
							'type' => $room->topLevelSpace->type->value,
						],
						'description' => $room->description,
						'capacity' => $room->capacity ? [
							'examCapacity' => $room->capacity->examCapacity,
							'normalCapacity' => $room->capacity->normalCapacity,
							'normal' => $room->capacity->normal,
							'exam' => $room->capacity->exam,
						] : null,
					],
					$event->locations
				),
				'isOngoing' => $isOngoing,
			];
		}, $this->classes);
	}
}
