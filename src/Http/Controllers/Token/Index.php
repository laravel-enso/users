<?php

namespace LaravelEnso\Users\Http\Controllers\Token;

use Illuminate\Routing\Controller;
use LaravelEnso\Users\Http\Resources\Token;
use LaravelEnso\Users\Models\User;

class Index extends Controller
{
    public function __invoke(User $user)
    {
        return Token::collection($user->tokens);
    }
}
