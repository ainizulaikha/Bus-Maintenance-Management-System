<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin-users', compact('users'));
    }
    
   public function store(Request $request)
    {
        
        $validated = $request->validate([
            'user_id' => ['required', 'string', 'size:6', 'regex:/^(AD|BD|ME)[0-9]{4}$/', 'unique:users,user_id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:Mechanic,Bus Driver'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $expectedPrefix = $validated['role'] === 'Mechanic' ? 'ME' : 'BD';

        if (!str_starts_with($validated['user_id'], $expectedPrefix)) {
            return back()
                ->withErrors(['user_id' => 'The User ID must match the selected role.'])
                ->withInput();
        }

        User::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/admin-users')
            ->with('success', 'Employee account created successfully.');
    }

    public function deactivate($id)
    {
        $user = User::whereIn('role', ['Mechanic', 'Bus Driver'])
            ->findOrFail($id);

        $user->is_active = false;
        $user->save();

        return redirect('/admin-users')
            ->with('success', 'Employee account deactivated successfully.');
    }

    public function activate($id)
    {
        $user = User::whereIn('role', ['Mechanic', 'Bus Driver'])
            ->findOrFail($id);

        $user->is_active = true;
        $user->save();

        return redirect('/admin-users')
            ->with('success', 'Employee account reactivated successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::whereIn('role', ['Mechanic', 'Bus Driver'])
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'role' => ['required', 'in:Mechanic,Bus Driver'],
        ]);

        $user->update($validated);

        return redirect('/admin-users')
            ->with('success', 'Employee details updated successfully.');
    }

}