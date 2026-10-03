<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Support\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['profile' => Profile::current()]);
    }

    public function update(Request $request)
    {
        $profile = Profile::current();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:5000'],
            'location' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'cv_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $data['skills'] = Tags::parse($data['skills'] ?? null);
        $data['available_for_work'] = $request->boolean('available_for_work');

        if ($request->hasFile('avatar') || $request->boolean('remove_avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $data['avatar'] = $request->hasFile('avatar')
                ? $request->file('avatar')->store('avatars', 'public')
                : null;
        } else {
            unset($data['avatar']);
        }

        unset($data['remove_avatar']);
        $profile->update($data);

        return back()->with('status', 'Perfil actualizado.');
    }
}
