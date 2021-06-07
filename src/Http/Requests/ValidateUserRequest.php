<?php

namespace LaravelEnso\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ValidateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'person_id' => ['exists:people,id', $this->personUnique()],
            'group_id' => 'required|exists:user_groups,id',
            'role_id' => 'required|exists:roles,id',
            'email' => ['email', 'required', $this->emailUnique()],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(Config::get('enso.auth.password.minLength'))
                    ->minUpperCase(Config::get('enso.auth.password.minUpperCase'))
                    ->hasMinNumeric(Config::get('enso.auth.password.minUpperCase')),
            ],
            'is_active' => 'boolean',
        ];
    }

    public function withValidator($validator)
    {
        if ($this->filled('password')) {
            $validator->after(fn ($validator) => (new PasswordValidator(
                $this,
                $validator,
                $this->route('user')
            ))->handle());
        }
    }

    protected function emailUnique()
    {
        return Rule::unique('people', 'email')->ignore($this->get('person_id'));
    }

    protected function personUnique()
    {
        return Rule::unique('users', 'person_id')->ignore($this->get('person_id'));
    }
}
