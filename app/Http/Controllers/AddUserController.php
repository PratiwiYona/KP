<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all(); 

        return view('adduser', compact('users'));
    }

    protected function store(Request $request)
    {
        $data = $request->validate([
            // 'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user instance and save it to the database
        User::create([
            'username' => $data['username'],
            'role' => $data['role'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->route('adduser')->with('success', 'User created successfully!'); 
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('adduser')->with('success', 'User deleted successfully!');
    }
}
