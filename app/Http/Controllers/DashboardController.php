<?php

namespace App\Http\Controllers;

use App\Application\DashboardService;
use App\ViewModels\CurrentCoursesAnnouncementsVM;
use App\ViewModels\CurrentEnrolledCoursesVM;
use App\ViewModels\CurriculumKpisVM;
use App\ViewModels\NextEvaluationsVM;
use App\ViewModels\PersonalInfoVM;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function __construct(private DashboardService $svc) {}

    public function index(): View
    {
        $userId = (int) auth()->id();

        $personalInfo = $this->svc->getMe($userId);
        $personalInfoVM = PersonalInfoVM::fromDomain($personalInfo);

        $ectsSum = $this->svc->getEctsSum($userId);

        $evaluations = $this->svc->getUpcomingEvaluations($userId);
        $evaluationsVM = NextEvaluationsVM::fromDomain($evaluations);

        $courses = $this->svc->getCurrentEnrolledCourses($userId);
        $coursesVM = CurrentEnrolledCoursesVM::fromDomain($courses);

        $announcements = $this->svc->listAnnouncements($userId);
        $announcementsVM = CurrentCoursesAnnouncementsVM::fromDomain($announcements);

        $kpis = $this->svc->curriculumKpis($userId);
        $kpisVM = CurriculumKpisVM::fromDomain($kpis);


        return view('dashboard.index', [
            'personalInfo' => $personalInfoVM->toArray(),
            'ectsSum' => $ectsSum,
            'evaluations' => $evaluationsVM->toArray(),
            'courses' => $coursesVM->toArray(),
            'announcements' => $announcementsVM->toArray(),
            'curriculum' => $kpisVM->toArray(),
        ]);
    }
}
