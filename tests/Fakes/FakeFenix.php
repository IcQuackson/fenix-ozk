<?php

namespace Tests\Fakes;

use App\Contracts\FenixPort;

final class FakeFenix implements FenixPort
{
	public function listCourses(int $userId): array
	{
		return [['id' => 'c1', 'name' => 'Algorithms', 'ects' => 6]];
	}

	public function listGrades(int $userId): array
	{
		return [['ects' => 6], ['ects' => 3]];
	}

	public function me(int $userId): array
	{
		return ['name' => 'Test User'];
	}
}
