<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Support\Tags;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        return view('admin.experiences.index', [
            'experiences' => Experience::ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.experiences.form', ['experience' => new Experience]);
    }

    public function store(Request $request)
    {
        Experience::create($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Experiencia añadida.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.form', ['experience' => $experience]);
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->update($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Experiencia actualizada.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('status', 'Experiencia eliminada.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:120'],
            'company_url' => ['nullable', 'url', 'max:255'],
            'period' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:3000'],
            'technologies' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['technologies'] = Tags::parse($data['technologies'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
