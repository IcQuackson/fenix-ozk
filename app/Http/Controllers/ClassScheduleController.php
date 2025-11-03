<?php

namespace App\Http\Controllers;

use App\Application\ClassScheduleService;
use App\ViewModels\NextClassesVM;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClassScheduleController extends Controller
{
	public function __construct(private ClassScheduleService $svc)
	{
	}

	public function next(Request $request): JsonResponse
	{
		$userId = (int) auth()->id();
		$limit = (int) $request->query('limit', 2);
		$limit = max(1, min($limit, 10));

		$classes = $this->svc->getNextClasses($userId, $limit);

		return response()->json(
			NextClassesVM::fromDomain($classes)->toArray()
		);
	}
}
