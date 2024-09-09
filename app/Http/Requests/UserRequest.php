<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     /**
     * on creation set validation rules
     *
     * @return array
     */
    protected function onCreate()
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required','in:Manager,User']
        ];
    }

     /**
     * on update set validation rules
     *
     * @return array
     */
    protected function onUpdate() 
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255','unique:users,email,'.$this->route('user')->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable','in:Manager,User']
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->isMethod('put') ? $this->onUpdate() : $this->onCreate();
    }
}
