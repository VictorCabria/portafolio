<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $this->syncAdminFromEnv($credentials);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son correctas.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Crea o actualiza el administrador definido en ADMIN_EMAIL / ADMIN_PASSWORD
     * cuando las credenciales enviadas coinciden exactamente con ellas.
     */
    private function syncAdminFromEnv(array $credentials): void
    {
        $email = config('auth.admin.email');
        $password = config('auth.admin.password');

        if (blank($email) || blank($password)) {
            return;
        }

        if (! hash_equals(strtolower($email), strtolower($credentials['email']))
            || ! hash_equals($password, $credentials['password'])) {
            return;
        }

        $user = User::firstOrNew(['email' => $email]);

        if ($user->exists && Hash::check($password, $user->password)) {
            return;
        }

        $user->name ??= 'Administrador';
        $user->password = $password;
        $user->save();
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
