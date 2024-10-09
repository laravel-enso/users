<?php

namespace LaravelEnso\Users\Services;

use Carbon\Carbon;
use LaravelEnso\Helpers\Services\Decimals;
use LaravelEnso\Users\Models\User;

class ProfileBuilder
{
    private const LoginRating = 80;

    private const ActionRating = 20;

    public function __construct(private User $user)
    {
    }

    public function set(): void
    {
        $this->user->load([
            'person:id,name,appellative,birthday,phone',
            'group:id,name', 'role:id,name', 'avatar:id,user_id',
        ]);

        $this->build();
    }

    public function build(): void
    {
        $this->user->loginCount = $this->user->logins()->count();
        $this->user->actionLogCount = $this->user->actionLogs()->count();
        $this->user->daysSinceMember = max((int) Carbon::parse($this->user->created_at)->diffInDays(true), 1);
        $this->user->rating = $this->rating();
    }

    private function rating(): int
    {
        $loginRatio = Decimals::div($this->user->loginCount, $this->user->daysSinceMember);
        $loginRating = Decimals::mul(self::LoginRating, $loginRatio);
        $actionRatio = Decimals::div($this->user->actionLogCount, $this->user->daysSinceMember);
        $actionRating = Decimals::mul(self::ActionRating, $actionRatio);
        $total = Decimals::add($loginRating, $actionRating);

        return (int) Decimals::div($total, 100);
    }
}
