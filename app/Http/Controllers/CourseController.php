<?php
namespace App\Http\Controllers;

use App\Application\CourseService;
use App\Application\PersonService;
use App\ViewModels\{
    CourseVM,
    CourseIndexVM,
    CourseEvaluationsVM,
    CourseGroupsVM,
    CourseScheduleVM,
    CourseStudentsVM
};
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class CourseController extends Controller
{
    public function __construct(
        private CourseService $svc,
        private PersonService $personService,
    )
    {
    }

    // ---- Web (Blade) ----
    public function index(): View
    {
        $userId = (int) auth()->id();
        $courses = $this->personService->getCurrentEnrolledCourses($userId);
        $vm = CourseIndexVM::fromDomain($courses);

        return view('courses.index', compact('vm'));
    }

    public function show(string $id): View
    {
        $course = $this->svc->getCourse($id);
        $vm = CourseVM::fromDomain($course);
        return view('courses.show', compact('vm'));
    }

    public function evaluations(string $id): View
    {
        $evaluations = $this->svc->listEvaluations($id);
        $vm = CourseEvaluationsVM::fromDomain($evaluations);
        return view('courses.evaluations', compact('vm'));
    }

    public function groups(string $id): View
    {
        $groups = $this->svc->listGroups($id);
        $vm = CourseGroupsVM::fromDomain($groups);
        return view('courses.groups', compact('vm'));
    }

    public function schedule(string $id): View
    {
        $schedule = $this->svc->getSchedule($id);
        $vm = CourseScheduleVM::fromDomain($schedule);
        return view('courses.schedule', compact('vm'));
    }

    public function students(string $id): View
    {
        $students = $this->svc->listStudents($id);
        $vm = CourseStudentsVM::fromDomain($students);
        return view('courses.students', compact('vm'));
    }

    // ---- API (JSON) ----
    public function apiIndex(): JsonResponse
    {
        $userId = (int) auth()->id();
        $courses = $this->personService->getCurrentEnrolledCourses($userId);
        $vm = CourseIndexVM::fromDomain($courses);

        return response()->json($vm->toArray());
    }

    public function apiShow(string $id): JsonResponse
    {
        $course = $this->svc->getCourse($id);
        return response()->json(CourseVM::fromDomain($course)->toArray());
    }

    public function apiEvaluations(string $id): JsonResponse
    {
        $evaluations = $this->svc->listEvaluations($id);
        return response()->json(CourseEvaluationsVM::fromDomain($evaluations)->toArray());
    }

    public function apiGroups(string $id): JsonResponse
    {
        $groups = $this->svc->listGroups($id);
        return response()->json(CourseGroupsVM::fromDomain($groups)->toArray());
    }

    public function apiSchedule(string $id): JsonResponse
    {
        $schedule = $this->svc->getSchedule($id);
        return response()->json(CourseScheduleVM::fromDomain($schedule)->toArray());
    }

    public function apiStudents(string $id): JsonResponse
    {
        $students = $this->svc->listStudents($id);
        return response()->json(CourseStudentsVM::fromDomain($students)->toArray());
    }
}
