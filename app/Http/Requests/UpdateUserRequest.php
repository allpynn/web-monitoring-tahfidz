<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $user = $this->route('user');
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/', 'unique:users,phone,'.$user->id],
            'role' => ['required', 'in:guru,orang_tua'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
