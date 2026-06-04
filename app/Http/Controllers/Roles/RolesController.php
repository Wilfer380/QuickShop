<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RolesController extends Controller
{
    public function index(): View
    {
        return view('roles.index');
    }
}
