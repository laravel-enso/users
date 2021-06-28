<?php

namespace LaravelEnso\Users\Policies;

use LaravelEnso\Roles\Models\Role;
use LaravelEnso\UserGroups\Models\UserGroup;
use LaravelEnso\Users\Models\User as Model;

class User
{
    public function before($user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function profile(Model $user, Model $targetUser)
    {
        return $user->isSupervisor() || $user->is($targetUser);
    }

    public function handle(Model $user, Model $targetUser)
    {
        return ! $targetUser->isAdmin()
            && $targetUser->group_id === $user->group_id;
    }

    public function changeGroup(Model $user, Model $targetUser)
    {
        return ! $targetUser->isAdmin()
            && $user->isSupervisor()
            && UserGroup::visible()->whereId($targetUser->group_id)->exists();
    }

    public function changeRole(Model $user, Model $targetUser)
    {
        return ! $targetUser->isAdmin()
            && $user->id !== $targetUser->id
            && Role::visible()->whereId($targetUser->role_id)->exists();
    }

    public function changePassword(Model $user, Model $targetUser)
    {
        return $this->allowed($user, $targetUser);
    }

    public function handleToken(Model $user, Model $targetUser)
    {
        return $this->allowed($user, $targetUser);
    }

    public function resetPassword(Model $user, Model $targetUser)
    {
        return $this->allowed($user, $targetUser);
    }

    public function handleSession(Model $user, Model $targetUser)
    {
        return $this->allowed($user, $targetUser);
    }

    protected function allowed(Model $user, Model $targetUser)
    {
        return $user->id === $targetUser->id
            || $this->isSuperior($user, $targetUser);
    }

    protected function isSuperior(Model $user, Model $targetUser): bool
    {
        return $user->isSupervisor() && ! $targetUser->isSupervisor()
            && ! $targetUser->isAdmin();
    }
}
