<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('users.create', [
            'roles' => User::ROLE_LABELS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:' . implode(',', array_keys(User::ROLE_LABELS)),
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => User::STATUS_PENDING, // 👈 CLAVE
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente. Estado: pendiente.');
    }

    /**
     * Formulario de edición
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user'     => $user,
            'roles'    => User::ROLE_LABELS,
            'statuses' => User::STATUS_LABELS,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'role'   => 'required|in:' . implode(',', array_keys(User::ROLE_LABELS)),
            'status' => 'required|in:' . implode(',', array_keys(User::STATUS_LABELS)),
        ]);

        // Seguridad básica: evitar que se auto-inactive el último admin
        if (
            $user->id === Auth::id() &&
            $validated['status'] !== User::STATUS_ACTIVE
        ) {
            return back()->withErrors([
                'status' => 'No podés desactivar tu propia cuenta.',
            ]);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminación lógica (opcional)
     */
    public function destroy(User $user)
    {
        $user->update([
            'status' => User::STATUS_INACTIVE,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario desactivado correctamente.');
    }
}
