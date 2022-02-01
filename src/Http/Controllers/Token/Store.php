<?php

namespace LaravelEnso\Users\Http\Controllers\Token;

use Illuminate\Routing\Controller;
use LaravelEnso\Users\Http\Requests\ValidateTokenRequest;
use LaravelEnso\Users\Models\User;

class Store extends Controller
{
    public function __invoke(ValidateTokenRequest $request, User $user)
    {
        return [
            'message' => 'Token was generated successfully',
            'token'   => $user->createToken($request->get('name'))->plainTextToken,
        ];
    }
}
