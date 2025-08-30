<?php
namespace App\Http\Controllers;

use App\Application\DashboardService;
use App\ViewModels\CurrentEnrolledCoursesVM;
use App\ViewModels\CurrentCoursesAnnouncementsVM;
use Illuminate\Contracts\View\View;
use App\ViewModels\NextEvaluationsVM;
use App\ViewModels\CurriculumKpisVM;

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

        $announcements = $this->svc->listAnnouncements((int) auth()->id());
        $announcementsVM = CurrentCoursesAnnouncementsVM::fromDomain($announcements);

        $kpis = $this->svc->curriculumKpis((int) auth()->id());
        $kpisVM = CurriculumKpisVM::fromDomain($kpis);

        return view('dashboard.index', [
            'summary' => [
                'me' => $summary['me'],
                'ectsSum' => $summary['ectsSum'],
                'evaluations' => $vm->toArray()
            ],
            'courses' => $coursesVM->toArray(),
            'announcements' => $announcementsVM->toArray(),
            'curriculum' => $kpisVM->toArray(),
        ]);
    }
}
