<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UsuariosController extends Controller
{
    public function index(): View
    {
        return view('usuarios.index');
    }
}
