<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $defaultCategories = [
            ['name' => 'Salário', 'type' => 'income', 'icon' => 'wallet', 'color' => '#10b981'],
            ['name' => 'Investimentos', 'type' => 'income', 'icon' => 'trending-up', 'color' => '#3b82f6'],
            ['name' => 'Alimentação', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#ef4444'],
            ['name' => 'Moradia', 'type' => 'expense', 'icon' => 'home', 'color' => '#f59e0b'],
            ['name' => 'Lazer', 'type' => 'expense', 'icon' => 'smile', 'color' => '#8b5cf6'],
        ];

foreach ($defaultCategories as $cat) {
    $user->categories()->create($cat);
}

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
