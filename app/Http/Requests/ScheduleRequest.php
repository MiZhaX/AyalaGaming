<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day' => 'required|in:Thursday,Friday',
            'time' => 'required|date_format:H:i',
            'event_id' => 'exists:events,id',
            'event_type' => 'required|in:Conference,Workshop'
        ];
    }

    public function messages(): array
    {
        return [
            'day.required' => 'El día es requerido',
            'day.in' => 'El día debe ser Thursday o Friday',
            'time.required' => 'La hora es requerida',
            'time.date_format' => 'La hora debe tener el formato HH:MM',
            'event_id.exists' => 'El evento no existe',
            'event_type.required' => 'El tipo de evento es requerido',
            'event_type.in' => 'El tipo de evento debe ser Conference o Workshop'
        ];
    }
}
