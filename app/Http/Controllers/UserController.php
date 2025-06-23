<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Helpers\EmailHelper;
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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->station = $request->station;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->password = Hash::make($request->password);
        $user->save();

        // Email Details
        $loginUrl = url('/login');
        $resetUrl = url('/forgot-password'); // Or use route('password.request')
        $terms = env('TERMS_AND_CONDITIONS', '#');

        $subject = "Your UCS Account Has Been Created";
        $message = "
            Dear {$user->name},<br><br>
            Your user account has been created successfully.<br>
            <strong>Login Email:</strong> {$user->email}<br>
            <strong>Temporary Password:</strong> Default123 <br><br>

            You are required to reset your password within <strong>24 hours</strong> using the link below:<br>
            <a href=\"{$resetUrl}\" target=\"_blank\">Reset Your Password</a><br><br>

            You can log in here: <a href=\"{$loginUrl}\" target=\"_blank\">Login to UCS Portal</a><br><br>

            <p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
            <p>Thank you for using Ufanisi Courier Services.</p>
        ";

        try {
            EmailHelper::sendHtmlEmail($user->email, $subject, $message);
        } catch (\Exception $e) {
            \Log::error("User Account Email Error: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'User account created.');
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

