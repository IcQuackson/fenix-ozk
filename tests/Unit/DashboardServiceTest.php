<?php

namespace Tests\Unit;

use App\Application\CourseService;
use App\Application\DashboardService;
use App\Application\InstitutionService;
use App\Application\PersonService;
use App\Contracts\FenixPort;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

final class DashboardServiceTest extends TestCase
{
	private Repository $dashboardCache;
	private Repository $personCache;
	private Repository $institutionCache;
	private Repository $courseCache;

	protected function setUp(): void
	{
		parent::setUp();

		Facade::setFacadeApplication(new Container());
		Log::swap(new NullLogger());

		$this->dashboardCache = new Repository(new ArrayStore());
		$this->personCache = new Repository(new ArrayStore());
		$this->institutionCache = new Repository(new ArrayStore());
		$this->courseCache = new Repository(new ArrayStore());
	}

	public function testGetEctsSumUsesLatestCurriculum(): void
	{
		$fenix = new FakeFenixPort([
			[
				'degree' => [
					'id' => 'deg-legacy',
					'acronym' => 'OLD',
					'name' => 'Legacy Degree',
				],
				'start' => '2018-09-01',
				'end' => '2022-07-15',
				'credits' => 180,
				'average' => 14.5,
				'calculatedAverage' => 14.5,
				'isFinished' => true,
				'numberOfApprovedCourses' => 40,
				'currentYear' => 4,
				'approvedCourses' => [],
			],
			[
				'degree' => [
					'id' => 'deg-current',
					'acronym' => 'NEW',
					'name' => 'Current Degree',
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
		]);

		$institutionService = new InstitutionService($fenix, $this->institutionCache);
		$courseService = new CourseService($fenix, $this->courseCache);
		$personService = new PersonService($fenix, $this->personCache, $institutionService, $courseService);
		$dashboard = new DashboardService($fenix, $this->dashboardCache, $personService, $institutionService);

		$sumFirstCall = $dashboard->getEctsSum(123);
		$sumSecondCall = $dashboard->getEctsSum(123);

		$this->assertSame(42.0, $sumFirstCall);
		$this->assertSame(42.0, $sumSecondCall);
		$this->assertSame(1, $fenix->personCurriculumCalls);
	}

	public function testGetEctsSumReturnsZeroWhenNoCurriculum(): void
	{
		$fenix = new FakeFenixPort([]);

		$institutionService = new InstitutionService($fenix, $this->institutionCache);
		$courseService = new CourseService($fenix, $this->courseCache);
		$personService = new PersonService($fenix, $this->personCache, $institutionService, $courseService);
		$dashboard = new DashboardService($fenix, $this->dashboardCache, $personService, $institutionService);

		$this->assertSame(0.0, $dashboard->getEctsSum(321));
		$this->assertSame(1, $fenix->personCurriculumCalls);
	}
}

/**
 * Minimal Fenix implementation for dashboard tests.
 *
 * Most methods are no-ops because the scenarios under test only exercise
 * curriculum and institution lookups.
 */
final class FakeFenixPort implements FenixPort
{
	public int $personCurriculumCalls = 0;

	/** @param array<int,array<string,mixed>> $curriculum */
	public function __construct(
		private array $curriculum,
		private array $about = ['currentAcademicTerm' => '1º Semestre 2024/2025'],
	) {
	}

	public function getAbout(?string $lang = null): array
	{
		return $this->about;
	}

	public function listAcademicTerms(?string $lang = null): array
	{
		return [];
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
		return [];
	}

	public function listCourseEvaluations(string $id, ?string $lang = null): array
	{
		return [];
	}

	public function listCourseGroups(string $id, ?string $lang = null): array
	{
		return [];
	}

	public function getCourseSchedule(string $id, ?string $lang = null): array
	{
		return [];
	}

	public function listCourseStudents(string $id, ?string $lang = null): array
	{
		return [];
	}

	public function listDegrees(?string $term = null, ?string $lang = null): array
	{
		return [];
	}

	public function getDegreeById(string $id, ?string $term = null, ?string $lang = null): array
	{
		return [];
	}

	public function listDegreeCourses(string $id, ?string $term = null, ?string $lang = null): array
	{
		return [];
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
		return [];
	}

	public function getPersonCalendarClasses(int $userId, ?string $format = 'json', ?string $lang = null): array|string
	{
		return [];
	}

	public function getPersonCalendarEvaluations(int $userId, ?string $format = 'json', ?string $lang = null): array|string
	{
		return [];
	}

	public function getPersonCourses(int $userId, ?string $term = null, ?string $lang = null): array
	{
		return [];
	}

	public function getPersonCurriculum(int $userId, ?string $lang = null): array
	{
		$this->personCurriculumCalls++;

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
		return [];
	}
}
