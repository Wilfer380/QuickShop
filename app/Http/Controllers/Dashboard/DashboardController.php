<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboardService): View
    {
        return view('dashboard', $dashboardService->summary());
    }
}
