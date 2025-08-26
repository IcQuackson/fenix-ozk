<?php
namespace App\Http\Controllers;

use App\Application\DashboardService;
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

        return view('dashboard.index', [
            'summary' => [
                'me' => $summary['me'],
                'ectsSum' => $summary['ectsSum'],
                'evaluations' => $vm->toArray(),
            ],
        ]);
    }
}
