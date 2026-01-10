<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ActionLog;

class AdminUserController extends Controller
{
    public function __construct()
    {
        // Only allow logged-in admins
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        // Log the creation
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'Created user ' . $user->username . ' (' . $user->email . ')',
        ]);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required',
        ]);

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Log the update
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'Updated user ' . $user->username . ' (' . $user->email . ')',
        ]);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        // Log the deletion before actually deleting
        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'User',
            'target_id' => $user->id,
            'description' => 'Deleted user ' . $user->username . ' (' . $user->email . ')',
        ]);

        $user->delete();
        
        return redirect()->route('admin.users.index');
    }
}
