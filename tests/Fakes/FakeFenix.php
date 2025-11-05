<?php

namespace Tests\Fakes;

use App\Contracts\FenixPort;

/**
 * Lightweight fake for feature tests.
 *
 * Provides deterministic responses covering the slice of the API exercised by
 * the dashboard and course pages so we can render views without hitting Fenix.
 */
final class FakeFenix implements FenixPort
{
	private array $course = [
		'id' => 'c1',
		'acronym' => 'ALG',
		'name' => 'Algorithms',
		'academicTerm' => '1º Semestre 2024/2025',
		'url' => 'https://example.test/courses/ALG',
		'announcementLink' => null,
		'ects' => 6,
	];

	private array $person = [
		'id' => '123456',
		'name' => 'Test User',
		'username' => 'ist123456',
		'institutionalEmail' => 'test.user@tecnico.ulisboa.pt',
		'photo' => null,
		'campus' => 'Alameda',
	];

	private array $curriculum = [
		[
			'degree' => [
				'id' => 'deg-current',
				'acronym' => 'MEIC',
				'name' => 'Mestrado em Engenharia Informática',
			],
			'start' => '2023-09-01',
			'end' => null,
			'credits' => 42,
			'average' => 16.0,
			'calculatedAverage' => 16.0,
			'isFinished' => false,
			'numberOfApprovedCourses' => 10,
			'currentYear' => 1,
			'approvedCourses' => [],
		],
	];

	public function getAbout(?string $lang = null): array
	{
		return ['currentAcademicTerm' => '1º Semestre 2024/2025'];
	}

	public function listAcademicTerms(?string $lang = null): array
	{
		return ['2023/2024' => ['1º Semestre 2023/2024']];
	}

	public function getDomainModel(?string $lang = null): array
	{
		return [];
	}

	public function getCanteenMenu(?string $lang = null): array
	{
		return [];
	}

	public function getContacts(?string $lang = null): array
	{
		return [];
	}

	public function getParking(?string $lang = null): array
	{
		return [];
	}

	public function getShuttle(?string $lang = null): array
	{
		return [];
	}

	public function getCourseById(string $id, ?string $lang = null): array
	{
		return $this->course;
	}

	public function listCourseEvaluations(string $id, ?string $lang = null): array
	{
		return [
			[
				'id' => 'eval-1',
				'type' => 'EXAM',
				'name' => 'Final Exam',
				'evaluationPeriod' => [
					'start' => '2024-02-01T09:00:00Z',
					'end' => '2024-02-01T12:00:00Z',
				],
				'courses' => [$this->course],
				'rooms' => [],
			],
		];
	}

	public function listCourseGroups(string $id, ?string $lang = null): array
	{
		return [
			[
				'id' => 'group-1',
				'shift' => 'T1',
				'students' => [
					['username' => 'ist123456', 'name' => 'Test User'],
				],
			],
		];
	}

	public function getCourseSchedule(string $id, ?string $lang = null): array
	{
		return [
			[
				'weekday' => 'Monday',
				'start' => '09:00',
				'end' => '11:00',
				'room' => 'VA1',
			],
		];
	}

	public function listCourseStudents(string $id, ?string $lang = null): array
	{
		return [
			[
				'username' => 'ist123456',
				'name' => 'Test User',
			],
		];
	}

	public function listDegrees(?string $term = null, ?string $lang = null): array
	{
		return [
			[
				'id' => 'deg-current',
				'acronym' => 'MEIC',
				'name' => 'Mestrado em Engenharia Informática',
			],
		];
	}

	public function getDegreeById(string $id, ?string $term = null, ?string $lang = null): array
	{
		return [
			'id' => 'deg-current',
			'name' => 'Mestrado em Engenharia Informática',
			'acronym' => 'MEIC',
		];
	}

	public function listDegreeCourses(string $id, ?string $term = null, ?string $lang = null): array
	{
		return [$this->course];
	}

	public function listSpaces(?string $lang = null): array
	{
		return [];
	}

	public function getSpaceById(string $id, ?string $day = null, ?string $lang = null): array
	{
		return [];
	}

	public function getSpaceBlueprint(string $id, string $format, ?string $lang = null): mixed
	{
		return '';
	}

	public function getPerson(int $userId, ?string $lang = null): array
	{
		return $this->person;
	}

	public function getPersonCalendarClasses(int $userId, ?string $format = 'json', ?string $lang = null): array|string
	{
		return ['events' => []];
	}

	public function getPersonCalendarEvaluations(int $userId, ?string $format = 'json', ?string $lang = null): array|string
	{
		return ['events' => []];
	}

	public function getPersonCourses(int $userId, ?string $term = null, ?string $lang = null): array
	{
		return [
			'enrolments' => [
				[
					'course' => $this->course,
					'grade' => null,
					'ects' => $this->course['ects'],
				],
			],
		];
	}

	public function getPersonCurriculum(int $userId, ?string $lang = null): array
	{
		return $this->curriculum;
	}

	public function getPersonEvaluations(int $userId, ?string $lang = null): array
	{
		return [];
	}

	public function updatePersonEvaluationEnrolment(int $userId, string $id, bool $enrol, ?string $lang = null): array
	{
		return [];
	}

	public function getPersonPayments(int $userId, ?string $lang = null): array
	{
		return ['pending' => [], 'completed' => []];
	}
}
