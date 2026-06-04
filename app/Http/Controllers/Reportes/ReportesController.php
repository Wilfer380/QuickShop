<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportesController extends Controller
{
    public function index(): View
    {
        return view('reportes.index');
    }
}
