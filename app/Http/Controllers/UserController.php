<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\userUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function createUser(UserCreateRequest $request): UserResource
    {
        $user = $this->userService->createUser($request);

        return new UserResource($user);

    }

    public function getUser($userId, Request $request): UserResource
    {
        $user = $this->userService->details($userId, $request);

        return new UserResource($user);

    }

    public function updateUser($userId, userUpdateRequest $userUpdateRequest): UserResource
    {
        $user = $this->userService->update($userId, $userUpdateRequest);

        return new UserResource($user);

    }

}
