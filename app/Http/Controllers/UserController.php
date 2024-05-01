<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Http\Resources\UserResource;

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
