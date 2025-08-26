<?php
namespace App\Jobs;

use App\Application\CourseService;
use App\Application\DashboardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RefreshUserSlices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $userId)
    {
    }

    public function handle(CourseService $courses, DashboardService $dashboard): void
    {
        $courses->listUserCourses($this->userId);
        $dashboard->summaryForUser($this->userId);
    }
}
