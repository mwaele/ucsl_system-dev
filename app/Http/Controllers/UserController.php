<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $stations = Office::all();
        $users = User::all();
        return view('users.index', compact('users', 'stations'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string',
            'role' => 'required|string',
            'station' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->station = $request->station;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('users.index')->with('success', 'User account created.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string',
            'station' => 'nullable|string',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->station = $request->station;
        $user->role = $request->role;
        $user->status = $request->status;

        $user->save();   

        return redirect()->back()->with('success', 'User updated successfully.');
    }

}

