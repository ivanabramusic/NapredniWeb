<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Prikaz svih korisnika
    public function index()
    {
        // Provjeri je li korisnik admin prije nego se dozvoli pristup
        if (auth()->user()->role_id != 1) {
            return redirect()->route('dashboard');
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Prikaz forme za uređivanje korisnika
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Dohvaćamo sve role (admin, nastavnik, student)
        return view('users.edit', compact('user', 'roles'));
    }

    // Spremanje promjena korisnika
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Korisnik uspješno ažuriran!');
    }
}
