<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function edit($id)
    {
    $user = User::findOrFail($id);
    return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
   {
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:4',
        'role' => 'required'
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role
    ];

    // Only update password if provided
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect('/users')->with('success', 'User updated successfully');
   }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role' => 'required'
        ]);

        User::create([
            'name' => 'Clare',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        return redirect('/users')->with('success', 'User created successfully');
    }

   public function destroy($id)
   {
    $user = User::findOrFail($id);
    $user->delete();

    return redirect('/users')->with('success', 'User deleted successfully');
  }
}
