<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'profile' => Profile::current(),
            'stats' => [
                'Proyectos' => Project::count(),
                'Publicados' => Project::published()->count(),
                'Destacados' => Project::where('is_featured', true)->count(),
                'Experiencias' => Experience::count(),
            ],
            'latest' => Project::latest()->limit(5)->get(),
        ]);
    }
}
