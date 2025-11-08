<?php

namespace App\ViewModels;

use App\Domain\Entities\ClassEvent;
use App\Domain\Entities\RoomRef;
use Carbon\CarbonImmutable;

final class ClassScheduleVM
{
	private const WEEKDAY_LABELS = [
		1 => 'Segunda-feira',
		2 => 'Terça-feira',
		3 => 'Quarta-feira',
		4 => 'Quinta-feira',
		5 => 'Sexta-feira',
		6 => 'Sábado',
		7 => 'Domingo',
	];

	/** @param array<int,array<string,mixed>> $events */
	private function __construct(
		private ?string $academicTerm,
		private array $events,
	) {
	}

	/**
	 * @param ClassEvent[] $events
	 */
	public static function fromDomain(?string $academicTerm, array $events): self
	{
		$now = CarbonImmutable::now();

		$payload = array_values(array_filter(array_map(
			static function (ClassEvent $event) use ($now) {
				$start = $event->classPeriod->start;
				if ($start === null) {
					return null;
				}

				$end = $event->classPeriod->end;

				$weekdayIndex = (int) $start->dayOfWeekIso;
				$weekdayLabel = self::WEEKDAY_LABELS[$weekdayIndex] ?? $start->format('l');

				$isOngoing = false;
				if ($start !== null) {
					$hasStarted = $start->lessThanOrEqualTo($now);
					$notFinished = $end === null || $end->greaterThanOrEqualTo($now);
					$isOngoing = $hasStarted && $notFinished;
				}

				$roomNames = array_map(
					static fn(RoomRef $room) => trim($room->name) === '' ? $room->id : $room->name,
					$event->locations
				);

				return [
					'title' => $event->title,
					'course' => [
						'id' => $event->course->id,
						'acronym' => $event->course->acronym,
						'name' => $event->course->name,
						'academicTerm' => $event->course->academicTerm,
					],
					'startIso' => $start->toIso8601String(),
					'endIso' => $end?->toIso8601String(),
					'dayIso' => $start->toDateString(),
					'weekdayIndex' => $weekdayIndex,
					'weekdayLabel' => $weekdayLabel,
					'startTime' => $start->format('H:i'),
					'endTime' => $end?->format('H:i'),
					'durationMinutes' => $end ? $start->diffInMinutes($end) : null,
					'roomNames' => $roomNames,
					'roomsLabel' => empty($roomNames) ? null : implode(', ', $roomNames),
					'rooms' => array_map(
						static fn(RoomRef $room) => [
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
			},
			$events
		)));

		return new self($academicTerm, $payload);
	}

	/**
	 * @return array{academicTerm: ?string, events: array<int,array<string,mixed>>}
	 */
	public function toArray(): array
	{
		return [
			'academicTerm' => $this->academicTerm,
			'events' => $this->events,
		];
	}
}
