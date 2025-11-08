<?php

namespace App\Http\Controllers;

use App\Application\ClassScheduleService;
use App\ViewModels\ClassScheduleVM;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class ScheduleController extends Controller
{
	public function __construct(
		private ClassScheduleService $classSchedule,
	) {
	}

	public function index(): View
	{
		$userId = (int) auth()->id();
		$schedule = $this->classSchedule->getFullSchedule($userId);

		$vm = ClassScheduleVM::fromDomain(
			$schedule['academicTerm'] ?? null,
			$schedule['events'] ?? []
		);

		return view('schedule.index', [
			'schedule' => $vm->toArray(),
		]);
	}

	public function data(): JsonResponse
	{
		$userId = (int) auth()->id();
		$schedule = $this->classSchedule->getFullSchedule($userId);

		return response()->json(
			ClassScheduleVM::fromDomain(
				$schedule['academicTerm'] ?? null,
				$schedule['events'] ?? []
			)->toArray()
		);
	}
}
