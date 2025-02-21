<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeakerRequest extends FormRequest
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
            'name' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialization' => 'required',
            'socialMedia' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'photo.required' => 'La foto es requerida',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif',
            'photo.max' => 'La imagen no debe pesar más de 2MB',
            'specialization.required' => 'La especialización es requerida',
            'socialMedia.required' => 'Las redes sociales son requeridas'
        ];
    }
}
