<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:inPerson,virtual'
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'El evento es requerido',
            'event_id.exists' => 'El evento no existe',
            'user_id.required' => 'El usuario es requerido',
            'user_id.exists' => 'El usuario no existe',
            'type.required' => 'El tipo de inscripción es requerido',
            'type.in' => 'El tipo de inscripción no es válido'
        ];
    }
}
