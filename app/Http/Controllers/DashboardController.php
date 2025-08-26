<?php
namespace App\Http\Controllers;

use App\Application\DashboardService;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function __construct(private DashboardService $svc)
    {
    }

    public function index(): View
    {
        $summary = $this->svc->summaryForUser((int) auth()->id());
        return view('dashboard.index', ['summary' => $summary]);
    }
}
