<?php
namespace App\Http\Controllers;

use App\Application\DashboardService;
use App\ViewModels\CurrentEnrolledCoursesVM;
use Illuminate\Contracts\View\View;
use App\ViewModels\NextEvaluationsVM;

final class DashboardController extends Controller
{
    public function __construct(private DashboardService $svc)
    {
    }

    public function index(): View
    {
        $summary = $this->svc->summaryForUser((int) auth()->id());
        $vm = NextEvaluationsVM::fromDomain($summary['evaluations']);
        $courses = $this->svc->getCurrentEnrolledCourses((int) auth()->id());
        $coursesVM = CurrentEnrolledCoursesVM::fromDomain($courses);

        return view('dashboard.index', [
            'summary' => [
                'me' => $summary['me'],
                'ectsSum' => $summary['ectsSum'],
                'evaluations' => $vm->toArray()
            ],
            'courses' => $coursesVM->toArray()
        ]);
    }
}
