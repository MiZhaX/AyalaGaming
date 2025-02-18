<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::all();

        if ($schedules->isEmpty()) {
            return response()->json([
                'message' => 'No se han encontrado eventos en el calendario',
                'status' => 200
            ], 200);
        }

        return response()->json([
            'schedules' => $schedules,
            'status' => 200
        ], 200);
    }

    // Método para crear un horario
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|in:Thursday,Friday',
            'time' => 'required|date_format:H:i',
            'event_id' => 'exists:events,id',
            'event_type' => 'required|in:Conference,Workshop'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Verificar que no haya otro evento del mismo tipo en el mismo horario
        $existingSchedule = Schedule::where('day', $request->day)
            ->where('time', $request->time)
            ->where('event_type', $request->event_type)
            ->whereNotNull('event_id')  // Asegurarse de que el horario esté ocupado por un evento
            ->exists();

        if ($existingSchedule) {
            return response()->json([
                'message' => 'Ya existe un evento de este tipo en este horario',
                'status' => 409
            ], 409);
        }

        $schedule = Schedule::create([
            'day' => $request->day,
            'time' => $request->time,
            'event_id' => $request->event_id,
            'event_type' => $request->event_type
        ]);

        return response()->json([
            'schedule' => $schedule,
            'status' => 201
        ], 201);
    }

    // Método para eliminar un horario por ID de evento
    public function deleteByEventId($eventId)
    {
        $schedule = Schedule::where('event_id', $eventId)->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'Horario no encontrado para este evento',
                'status' => 404
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'message' => 'Horario eliminado',
            'status' => 200
        ], 200);
    }

    // Método para actualizar un horario por ID de evento
    public function updateByEventId(Request $request, $eventId)
    {
        $schedule = Schedule::where('event_id', $eventId)->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'Horario no encontrado para este evento',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'day' => 'required|in:Thursday,Friday',
            'time' => 'required|date_format:H:i',
            'event_type' => 'required|in:Conference,Workshop'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Verificar que no haya otro evento del mismo tipo en el mismo horario
        $existingSchedule = Schedule::where('day', $request->day)
            ->where('time', $request->time)
            ->where('event_type', $request->event_type)
            ->where('event_id', '!=', $eventId)
            ->exists();

        if ($existingSchedule) {
            return response()->json([
                'message' => 'Ya existe un evento de este tipo en este horario',
                'status' => 409
            ], 409);
        }

        // Actualizar el horario
        $schedule->update([
            'day' => $request->day,
            'schedule' => $request->schedule,
            'event_type' => $request->event_type
        ]);

        return response()->json([
            'schedule' => $schedule,
            'status' => 200
        ], 200);
    }

    public function show($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Horario no encontrado',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'schedule' => $schedule,
            'status' => 200
        ], 200);
    }

    public function delete($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Horario no encontrado',
                'status' => 404
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'message' => 'Horario eliminado',
            'status' => 200
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Horario no encontrado',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'day' => 'required|in:Thursday,Friday',
            'time' => 'required',
            'event_type' => 'required|in:Conference,Workshop',
            'event_id' => 'required|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Validar que no haya otro evento del mismo tipo en ese horario
        $exists = Schedule::where('day', $request->day)
            ->where('time', $request->time)
            ->where('event_type', $request->event_type)
            ->where('id', '!=', $id) // Excluir el mismo registro
            ->whereNotNull('event_id')
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe un evento de este tipo en ese horario.',
                'status' => 400
            ], 400);
        }

        $schedule->update([
            'day' => $request->day,
            'time' => $request->time,
            'event_type' => $request->event_type,
            'event_id' => $request->event_id
        ]);

        return response()->json([
            'message' => 'Horario actualizado',
            'schedule' => $schedule,
            'status' => 200
        ], 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'message' => 'Horario no encontrado',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'day' => 'sometimes|in:Thursday,Friday',
            'time' => 'sometimes',
            'event_type' => 'sometimes|in:Conference,Workshop',
            'event_id' => 'sometimes|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        if ($request->has('day')) {
            $schedule->day = $request->day;
        }

        if ($request->has('time')) {
            $schedule->time = $request->time;
        }

        if ($request->has('event_type')) {
            $schedule->event_type = $request->event_type;
        }

        if ($request->has('event_id')) {
            $schedule->event_id = $request->event_id;
        }

        $schedule->save();

        return response()->json([
            'message' => 'Horario actualizado',
            'schedule' => $schedule,
            'status' => 200
        ], 200);
    }

    public function getAvailableSchedules(Request $request)
    {
        // Obtener los parámetros de la solicitud
        $day = $request->query('day');
        $type = $request->query('type');

        // Validar los parámetros
        if (!in_array($day, ['Thursday', 'Friday']) || !in_array($type, ['Conference', 'Workshop'])) {
            return response()->json(['error' => 'Parámetros inválidos'], 400);
        }

        // Obtener los horarios disponibles de la base de datos
        $schedules = Schedule::where('day', $day)
            ->where('event_type', $type)
            ->whereNull('event_id') // Filtrar donde el event_id es nulo
            ->pluck('time');  // Suponiendo que tienes una columna 'time'

        // Si no hay horarios disponibles
        if ($schedules->isEmpty()) {
            return response()->json(['message' => 'No hay horarios disponibles'], 404);
        }

        // Retornar los horarios disponibles como respuesta JSON
        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }
}
