<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    public function index(): View
    {
        return view('configuracion.index');
    }
}
