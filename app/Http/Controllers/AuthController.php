<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index() {
        return view ('auth.index');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => 'email|required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Credenciais inválidas.']);
        }

        auth()->login($user);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Deslogado com sucesso.');
    }

    public function user() {
        $user = auth()->user();
        $users = User::all();

        return view ('user.index', compact('user', 'users'));
    }

    public function create() {
        return view ('user.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'     => 'string|required',
            'email'    => 'email|required|unique:users,email',
            'role'     => 'string|required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*?&]/'
            ]
        ], [
            'email.unique'       => 'E-mail já cadastrado.',
            'password.regex'     => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role
        ]);

        return redirect()->route('usuario.user')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user) {
        return view ('user.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name'     => 'string|required',
            'email'    => 'email|required|unique:users,email,' . $user->id,
            'role'     => 'string|required',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/', 
                'regex:/[0-9]/', 
                'regex:/[@$!%*?&]/'
            ]
        ], [
            'email.unique'       => 'E-mail já cadastrado.',
            'password.regex'     => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.'
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role
        ]);

        if (filled($request->password)) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return redirect()->route('usuario.user')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user) {
        $user->delete();

        return redirect()->route('usuario.user')->with('success', 'Usuário excluído com sucesso.');
    }
}