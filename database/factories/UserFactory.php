<?php

namespace LaravelEnso\Users\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelEnso\People\Models\Person;
use LaravelEnso\Roles\Models\Role;
use LaravelEnso\UserGroups\Models\UserGroup;
use LaravelEnso\Users\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'person_id' => Person::factory()->test(),
            'group_id' => UserGroup::factory(),
            'email' => fn ($attributes) => Person::find($attributes['person_id'])->email,
            'role_id' => Role::factory(),
            'is_active' => $this->faker->boolean,
        ];
    }
}
