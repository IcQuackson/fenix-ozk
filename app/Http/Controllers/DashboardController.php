<?php

namespace App\Http\Controllers;

use App\Application\DashboardService;
use App\Application\ClassScheduleService;
use App\ViewModels\CurrentCoursesAnnouncementsVM;
use App\ViewModels\CurrentEnrolledCoursesVM;
use App\ViewModels\CurriculumKpisVM;
use App\ViewModels\NextEvaluationsVM;
use App\ViewModels\PersonalInfoVM;
use App\ViewModels\NextClassesVM;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $svc,
        private ClassScheduleService $classScheduleService,
    ) {}

    public function index(): View
    {
        $userId = (int) auth()->id();

        $personalInfo = $this->svc->getMe($userId);
        $personalInfoVM = PersonalInfoVM::fromDomain($personalInfo);

        $ectsSum = $this->svc->getEctsSum($userId);

        $evaluations = $this->svc->getUpcomingEvaluations($userId);
        $evaluationsVM = NextEvaluationsVM::fromDomain($evaluations);

        $classes = $this->classScheduleService->getNextClasses($userId, 2);
        $classesVM = NextClassesVM::fromDomain($classes);

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
            'classes' => $classesVM->toArray(),
            'courses' => $coursesVM->toArray(),
            'announcements' => $announcementsVM->toArray(),
            'curriculum' => $kpisVM->toArray(),
        ]);
    }
}
