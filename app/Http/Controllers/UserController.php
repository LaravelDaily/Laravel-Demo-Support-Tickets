<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->paginate();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::pluck('name', 'id');

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $user->assignRole($request->input('role'));

        return to_route('users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(EditUserRequest $request, User $user)
    {
        if ($request->has('password')) {
            $user->update(['password' => bcrypt($request->input('password'))]);
        }

        $user->update([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => $request->has('password') ? bcrypt($request->input('password')) : '',
        ]);

        $user->syncRoles($request->input('role'));

        return to_route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return to_route('users.index');
    }
}
