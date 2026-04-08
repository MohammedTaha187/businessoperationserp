<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\StoreUserRequest;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return $this->paginatedResponse($users, __('lang.Users retrieved successfully'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return $this->successResponse(new UserResource($user), __('lang.User created successfully'), 201);
    }

    public function show(User $user)
    {
        return $this->successResponse(new UserResource($user), __('lang.User retrieved successfully'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return $this->successResponse(new UserResource($user), __('lang.User updated successfully'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse(null, __('lang.User deleted successfully'));
    }
}
