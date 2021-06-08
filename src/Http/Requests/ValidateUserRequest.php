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
                Password::defaults(),
                fn ($field, $password, $fail) => $this
                    ->distinctPassword($password, $fail),
            ],
            'is_active' => 'boolean',
        ];
    }

    public function withValidator($validator)
    {
        if ($this->filled('password')) {
            if ($this->route('user')->currentPasswordIs($this->get('password'))) {
                $validator->errors()
                    ->add('password', __('You cannot use the existing password'));
            }
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

    private function distinctPassword($password, $fail)
    {
        if ($this->filled('password')) {
            if ($this->route('user')->currentPasswordIs($password)) {
                $fail(__('You cannot use the existing password'));
            }
        }
    }
}
