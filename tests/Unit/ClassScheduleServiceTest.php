<?php

namespace Tests\Unit;

use App\Application\ClassScheduleService;
use App\Contracts\FenixPort;
use Carbon\CarbonImmutable;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ClassScheduleServiceTest extends TestCase
{
	private Repository $cache;

	protected function setUp(): void
	{
		parent::setUp();
		$this->cache = new Repository(new ArrayStore());
	}

	protected function tearDown(): void
	{
		CarbonImmutable::setTestNow();
		parent::tearDown();
	}

	public function testReturnsEmptyWhenNoUpcomingEvents(): void
	{
		CarbonImmutable::setTestNow(CarbonImmutable::parse('2024-03-01T10:00:00Z'));

		/** @var FenixPort&MockObject $fenix */
		$fenix = $this->createMock(FenixPort::class);
		$fenix->expects($this->once())
			->method('getPersonCalendarClasses')
			->with(123)
			->willReturn(['events' => []]);

		$service = new ClassScheduleService($fenix, $this->cache);

		$this->assertSame([], $service->getNextClasses(123));
	}

	public function testOngoingClassComesFirst(): void
	{
		CarbonImmutable::setTestNow(CarbonImmutable::parse('2024-03-01T09:30:00+00:00'));

		$events = [
			$this->makeEvent(
				title: 'Algebra Lecture',
				start: '2024-03-01T09:00:00+00:00',
				end: '2024-03-01T10:00:00+00:00'
			),
			$this->makeEvent(
				title: 'Physics Lab',
				start: '2024-03-01T11:00:00+00:00',
				end: '2024-03-01T12:00:00+00:00',
				courseId: 'PHY101',
				courseName: 'Physics I'
			),
		];

		/** @var FenixPort&MockObject $fenix */
		$fenix = $this->createMock(FenixPort::class);
		$fenix->expects($this->once())
			->method('getPersonCalendarClasses')
			->with(42)
			->willReturn(['events' => $events]);

		$service = new ClassScheduleService($fenix, $this->cache);

		$result = $service->getNextClasses(42, 2);

		$this->assertCount(2, $result);
		$this->assertSame('Algebra Lecture', $result[0]->title);
		$this->assertSame('Physics Lab', $result[1]->title);
	}

	public function testOverlappingClassesKeepChronologicalOrder(): void
	{
		CarbonImmutable::setTestNow(CarbonImmutable::parse('2024-03-01T08:30:00+00:00'));

		$events = [
			// Later start provided first on purpose (unsorted)
			$this->makeEvent(
				title: 'Software Engineering',
				start: '2024-03-01T10:30:00+00:00',
				end: '2024-03-01T12:00:00+00:00',
				courseId: 'SE200',
				courseName: 'Software Engineering'
			),
			$this->makeEvent(
				title: 'Distributed Systems',
				start: '2024-03-01T10:00:00+00:00',
				end: '2024-03-01T11:30:00+00:00',
				courseId: 'DS150',
				courseName: 'Distributed Systems'
			),
			$this->makeEvent(
				title: 'Databases',
				start: '2024-03-01T13:00:00+00:00',
				end: '2024-03-01T14:30:00+00:00',
				courseId: 'DB110',
				courseName: 'Databases'
			),
		];

		/** @var FenixPort&MockObject $fenix */
		$fenix = $this->createMock(FenixPort::class);
		$fenix->expects($this->once())
			->method('getPersonCalendarClasses')
			->with(7)
			->willReturn(['events' => $events]);

		$service = new ClassScheduleService($fenix, $this->cache);

		$result = $service->getNextClasses(7, 2);

		$this->assertCount(2, $result);
		$this->assertSame('Distributed Systems', $result[0]->title);
		$this->assertSame('Software Engineering', $result[1]->title);
	}

	/**
	 * @return array<string,mixed>
	 */
	private function makeEvent(
		string $title,
		string $start,
		string $end,
		string $courseId = 'ALG101',
		string $courseName = 'Algebra',
		string $courseAcronym = 'ALG',
		string $academicTerm = '2024/2025'
	): array {
		return [
			'title' => $title,
			'classPeriod' => [
				'start' => $start,
				'end' => $end,
			],
			'course' => [
				'id' => $courseId,
				'acronym' => $courseAcronym,
				'name' => $courseName,
				'academicTerm' => $academicTerm,
			],
			'location' => [],
		];
	}
}
