<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;

class PortfolioController extends Controller
{
    public function home()
    {
        return view('portfolio.home', [
            'profile' => Profile::current(),
            'experiences' => Experience::ordered()->get(),
            'projects' => Project::published()->where('is_featured', true)->ordered()->get(),
            'projectsCount' => Project::published()->count(),
        ]);
    }

    public function projects()
    {
        return view('portfolio.projects', [
            'profile' => Profile::current(),
            'projects' => Project::published()->ordered()->get(),
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->is_published || auth()->check(), 404);

        return view('portfolio.show', [
            'profile' => Profile::current(),
            'project' => $project,
            'others' => Project::published()->whereKeyNot($project->id)->ordered()->limit(3)->get(),
        ]);
    }
}
