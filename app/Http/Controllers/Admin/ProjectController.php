<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', [
            'projects' => Project::ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project([
            'is_published' => true,
            'year' => now()->year,
        ])]);
    }

    public function store(Request $request)
    {
        $project = Project::create($this->validated($request));

        return redirect()->route('admin.projects.index')->with('status', "Proyecto \"{$project->title}\" creado.");
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($this->validated($request, $project));

        return redirect()->route('admin.projects.index')->with('status', "Proyecto \"{$project->title}\" actualizado.");
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Proyecto eliminado.');
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'alpha_dash', 'max:160', Rule::unique('projects', 'slug')->ignore($project)],
            'summary' => ['required', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:20000'],
            'technologies' => ['nullable', 'string', 'max:500'],
            'client' => ['nullable', 'string', 'max:120'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $data['technologies'] = Tags::parse($data['technologies'] ?? null);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image') || $request->boolean('remove_image')) {
            if ($project?->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->hasFile('image')
                ? $request->file('image')->store('projects', 'public')
                : null;
        } else {
            unset($data['image']);
        }

        unset($data['remove_image']);

        return $data;
    }
}
