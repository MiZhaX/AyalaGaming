<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\Schedule;

class EventController extends Controller
{
    protected $scheduleController;

    public function __construct(ScheduleController $scheduleController)
    {
        $this->scheduleController = $scheduleController;
    }

    // Método para la validación reutilizable
    protected function validateEvent(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'speaker_id' => 'required|exists:speakers,id',
            'day' => 'required|in:Thursday,Friday',
            'time' => 'required|date_format:H:i',
            'type' => 'required|in:Conference,Workshop'
        ]);
    }

    public function index()
    {
        // Cargar los eventos junto con su horario relacionado
        $events = Event::with('schedule')->get();

        if ($events->isEmpty()) {
            return response()->json([
                'message' => 'No se han encontrado eventos',
                'status' => 200
            ], 200);
        }

        // Formatear los datos antes de enviarlos
        $eventsData = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'inPersonAssistance' => $event->inPersonAssistance,
                'virtualAssistance' => $event->virtualAssistance,
                'speaker_id' => $event->speaker_id,
                'schedule' => $event->schedule ? [
                    'day' => $event->schedule->day,
                    'time' => $event->schedule->time,
                    'type' => $event->schedule->event_type,
                ] : null
            ];
        });

        return response()->json([
            'events' => $eventsData,
            'status' => 200
        ], 200);
    }

    public function store(EventRequest $request)
    {
        if (!$request->validated()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $request->messages(),
                'status' => 400
            ], 400);
        }

        // Verificar si el ponente ya tiene un evento en el mismo día y hora
        $existingSpeakerEvent = Schedule::where('day', $request->day)
            ->where('time', $request->time)
            ->whereHas('event', function ($query) use ($request) {
                $query->where('speaker_id', $request->speaker_id);
            })
            ->exists();

        if ($existingSpeakerEvent) {
            return response()->json([
                'message' => 'El ponente ya tiene un evento asignado en este horario',
                'status' => 400
            ], 400);
        }

        // Verificar si ya existe un horario con el mismo día, hora y tipo de evento
        $existingSchedule = Schedule::where('day', $request->day)
            ->where('time', $request->time)
            ->where('event_type', $request->type)
            ->whereNull('event_id') // Asegurarse de que no esté ocupado
            ->first();

        if (!$existingSchedule) {
            return response()->json([
                'message' => 'No se pudo encontrar un horario disponible para este evento',
                'status' => 400
            ], 400);
        }

        // Crear el evento
        $event = Event::create([
            'name' => $request->name,
            'inPersonAssistance' => 0,
            'virtualAssistance' => 0,
            'speaker_id' => $request->speaker_id
        ]);

        // Si no se crea el evento correctamente, se responde con error
        if (!$event) {
            return response()->json([
                'message' => 'Error al crear el evento',
                'status' => 500
            ], 500);
        }

        // Asignar el ID del evento al horario encontrado
        $existingSchedule->event_id = $event->id;
        $existingSchedule->save();

        return response()->json([
            'event' => $event,
            'schedule' => $existingSchedule,
            'status' => 201
        ], 201);
    }

    public function show($id)
    {
        $event = Event::with('schedule')->find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Evento no encontrado',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'event' => $event,
            'status' => 200
        ], 200);
    }

    public function delete($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Evento no encontrado',
                'status' => 404
            ], 404);
        }

        // Verificar si hay asistentes registrados
        if ($event->inPersonAssistance > 0 || $event->virtualAssistance > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el evento porque tiene asistentes registrados.',
                'status' => 400
            ], 400);
        }

        // Actualizar el horario del evento en Schedule (poniendo event_id a null)
        if ($event->schedule) {
            $event->schedule->update(['event_id' => null]);
        }

        // Eliminar el evento
        $event->delete();

        return response()->json([
            'message' => 'Evento eliminado y horario actualizado',
            'status' => 200
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Evento no encontrado',
                'status' => 404
            ], 404);
        }

        // Validación de los datos entrantes
        $validator = $this->validateEvent($request);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // Actualizar evento
        $event->update([
            'name' => $request->name,
            'speaker_id' => $request->speaker_id
        ]);

        // Eliminar la asignación de evento del horario anterior
        $previousSchedule = Schedule::where('event_id', $event->id)->first();
        if ($previousSchedule) {
            $previousSchedule->event_id = null; // Liberar el horario
            $previousSchedule->save();
        }

        // Verificar si ya existe un horario con el nuevo día y hora
        $newSchedule = Schedule::where('day', $request->day)
            ->where('time', $request->time)
            ->where('event_type', $request->type)
            ->whereNull('event_id') // Asegurarse de que el horario esté libre
            ->first();

        if (!$newSchedule) {
            return response()->json([
                'message' => 'No se pudo encontrar un horario disponible para este evento',
                'status' => 400
            ], 400);
        }

        // Asignar el nuevo horario
        $newSchedule->event_id = $event->id;
        $newSchedule->save();

        return response()->json([
            'message' => 'Evento actualizado',
            'event' => $event,
            'schedule' => $newSchedule,
            'status' => 200
        ], 200);
    }


    public function updatePartial(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Evento no encontrado',
                'status' => 404
            ], 404);
        }

        // Validaciones de límites de asistencia
        $inPerson = $request->inPersonAssistance ?? $event->inPersonAssistance;
        $virtual = $request->virtualAssistance ?? $event->virtualAssistance;
        $eventType = $request->type ?? $event->schedule->event_type; // Obtener el tipo actual si no se envía en la solicitud

        if ($virtual > 20) {
            return response()->json([
                'message' => 'La asistencia virtual no puede superar los 20 participantes.',
                'status' => 400
            ], 400);
        }

        if ($eventType === 'Conference' && $inPerson > 30) {
            return response()->json([
                'message' => 'La asistencia presencial para conferencias no puede superar los 30 participantes.',
                'status' => 400
            ], 400);
        }

        if ($eventType === 'Workshop' && $inPerson > 15) {
            return response()->json([
                'message' => 'La asistencia presencial para talleres no puede superar los 15 participantes.',
                'status' => 400
            ], 400);
        }

        // Actualizar los campos permitidos
        $event->update($request->only('name', 'inPersonAssistance', 'virtualAssistance', 'speaker_id'));

        // Si el request incluye datos de horario, actualizarlo
        if ($request->has(['day', 'time', 'type'])) {
            $scheduleData = [
                'day' => $request->day,
                'time' => $request->time,
                'event_id' => $event->id,
                'event_type' => $request->type
            ];
            $scheduleResponse = $this->scheduleController->updateByEventId(new Request($scheduleData), $id);

            return response()->json([
                'message' => 'Evento y horario actualizados',
                'event' => $event,
                'schedule' => json_decode($scheduleResponse->getContent()),
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Evento actualizado',
            'event' => $event,
            'status' => 200
        ], 200);
    }
}
