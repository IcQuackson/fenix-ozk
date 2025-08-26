<?php
namespace App\Http\Controllers;

use App\Application\CourseService;
use App\ViewModels\CoursesVM;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class CourseController extends Controller
{
    public function __construct(private CourseService $svc)
    {
    }

    public function index(): View
    {
        $courses = $this->svc->listUserCourses((int) auth()->id());
        $vm = CoursesVM::fromDomain($courses);
        return view('courses.index', compact('vm'));
    }

    public function apiIndex(): JsonResponse
    {
        $courses = $this->svc->listUserCourses((int) auth()->id());
        return response()->json(CoursesVM::fromDomain($courses)->toArray());
    }
}
