<?php

namespace LaravelEnso\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use LaravelEnso\Core\Rules\DistinctPassword;

class ValidateUser extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'person_id' => ['exists:people,id', $this->personUnique()],
            'group_id'  => 'required|exists:user_groups,id',
            'role_id'   => 'required|exists:roles,id',
            'email'     => ['email:rfc,dns', 'required', $this->emailUnique()],
            'password'  => $this->password(),
            'is_active' => 'boolean',
        ];
    }

    protected function emailUnique()
    {
        return Rule::unique('people', 'email')
            ->ignore($this->get('person_id'));
    }

    protected function personUnique()
    {
        return Rule::unique('users', 'person_id')
            ->ignore($this->route('user'));
    }

    private function password(): array
    {
        $rules = ['nullable', 'confirmed', Password::defaults()];

        if ($this->route('user')) {
            $rules[] = new DistinctPassword($this->route('user'));
        }

        return $rules;
    }
}
