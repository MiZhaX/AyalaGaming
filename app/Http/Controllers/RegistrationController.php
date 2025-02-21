<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::with(['event.schedule', 'user'])->get()->groupBy('user_id');

        if ($registrations->isEmpty()) {
            $data = [
                'message' => 'No se han encontrado inscripciones',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'registrations' => $registrations,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:inPerson,virtual'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Obtener el evento con su tipo
        $event = Event::with('schedule')->find($request->event_id);

        if (!$event) {
            return response()->json([
                'message' => 'Evento no encontrado',
                'status' => 404
            ], 404);
        }

        $eventType = $event->schedule->event_type;

        // Definir límites de asistencia
        $maxInPerson = ($eventType === 'Conference') ? 30 : 15;
        $maxVirtual = 20;

        // Validar disponibilidad de asistencia
        if ($request->type === 'inPerson' && $event->inPersonAssistance >= $maxInPerson) {
            return response()->json([
                'message' => 'No hay más espacio disponible para la asistencia presencial.',
                'status' => 400
            ], 400);
        }

        if ($request->type === 'virtual' && $event->virtualAssistance >= $maxVirtual) {
            return response()->json([
                'message' => 'No hay más espacio disponible para la asistencia virtual.',
                'status' => 400
            ], 400);
        }

        // Validar límite de inscripciones por usuario
        $userId = $request->user_id;
        $userRegistrations = Registration::where('user_id', $userId)
            ->whereHas('event.schedule', function ($query) use ($eventType) {
                $query->where('event_type', $eventType);
            })
            ->count();

        $maxUserRegistrations = ($eventType === 'Conference') ? 5 : 4;

        if ($userRegistrations >= $maxUserRegistrations) {
            return response()->json([
                'message' => "No puedes inscribirte en más de $maxUserRegistrations $eventType(s).",
                'status' => 400
            ], 400);
        }

        // Crear la inscripción
        $registration = Registration::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'type' => $request->type
        ]);

        if (!$registration) {
            return response()->json([
                'message' => 'Error al crear la inscripción',
                'status' => 500
            ], 500);
        }

        // Actualizar las asistencias del evento
        if ($request->type === 'inPerson') {
            $event->increment('inPersonAssistance');
        } elseif ($request->type === 'virtual') {
            $event->increment('virtualAssistance');
        }

        return response()->json([
            'registration' => $registration,
            'status' => 201
        ], 201);
    }

    public function show($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            $data = [
                'message' => 'Inscripción no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'registration' => $registration,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            $data = [
                'message' => 'Inscripción no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $registration->delete();

        $data = [
            'message' => 'Inscripción eliminada',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            $data = [
                'message' => 'Inscripción no encontrada',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'user_id' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $registration->event_id = $request->event_id;
        $registration->user_id = $request->user_id;
        $registration->type = $request->type;

        $registration->save();
        $data = [
            'message' => 'Inscripción actualizada',
            'registration' => $registration,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartialSpeaker(Request $request, $id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json([
                'message' => 'Inscripción no encontrada',
                'status' => 404
            ], 404);
        }

        $registration->update($request->only('event_id', 'user_id', 'type'));

        return response()->json([
            'message' => 'Inscripción actualizada',
            'registration' => $registration,
            'status' => 200
        ], 200);
    }
}
