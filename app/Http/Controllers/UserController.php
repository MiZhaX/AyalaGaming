<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use function Laravel\Prompts\alert;

class UserController extends Controller
{
    public function showCompleteRegistration()
    {
        return view('auth.complete-registration');
    }
    
    public function showCompletePayment(Request $request) 
    {
        $isStudent = $request->input('is_student', false);

        return view('auth.complete-payment', compact('isStudent'));
    }

    public function completeIsStudent(Request $request)
    {
        $request->validate([
            'is_student' => 'required|boolean',
            'student_code' => 'required_if:is_student,1', // Solo requerido si es estudiante
        ]);

        // Si el usuario es estudiante, validar el código
        if ($request->is_student == 1) {
            $expectedCode = Config::get('app.student_verification_code', env('STUDENT_VERIFICATION_CODE'));
            if ($request->student_code !== $expectedCode) {
                return back()->with('error', 'Código incorrecto.');
            }
        }

        // Guardar la información
        $user = Auth::user();
        $user->is_student = $request->is_student;
        $user->save();

        return view('auth.complete-events');
    }
}
