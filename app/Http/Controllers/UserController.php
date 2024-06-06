<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function store(CreateRequest $request): JsonResource
    {
        $data = $request->validated();

        $user = User::create($data);

        event(new Registered($user));

        return new UserResource($user);
    }
}
