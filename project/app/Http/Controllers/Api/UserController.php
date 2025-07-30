<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\User;

class UserController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->getAllUsers());
    }

    public function show(User $user)
    {
        return response()->json($this->userService->getUserById($user->id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        return response()->json($this->userService->createUser($validated), 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        return response()->json($this->userService->updateUser($user->id, $validated));
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user->id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
