<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'user_id' => ['required','numeric','exists:users,id']
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
            'status' => ['required','in:pending,in_progress,completed'],
            'user_id' => ['required','numeric','exists:users,id']
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
