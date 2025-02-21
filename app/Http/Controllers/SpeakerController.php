<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Speaker;
use App\Services\UploadService;
use App\Models\Event;
use App\Http\Requests\SpeakerRequest;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::all();

        if ($speakers->isEmpty()) {
            $data = [
                'message' => 'No se han encontrado ponentes',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'speakers' => $speakers,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(SpeakerRequest $request, UploadService $uploadService)
    {
        if (!$request->validated()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $request->messages(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $socialMedia = is_string($request->socialMedia)
            ? json_decode($request->socialMedia, true)
            : $request->socialMedia;

        $photoPath = $uploadService->upload($request->file('photo'), 'speakers');

        $speaker = Speaker::create([
            'name' => $request->name,
            'photo' => $photoPath,
            'specialization' => $request->specialization,
            'socialMedia' => $socialMedia,
        ]);

        if (!$speaker) {
            $data = [
                'message' => 'Error al crear el ponente',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'speaker' => $speaker,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $speaker = Speaker::find($id);

        if (!$speaker) {
            $data = [
                'message' => 'Ponente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'speaker' => $speaker,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function delete($id, UploadService $uploadService)
    {
        $speaker = Speaker::find($id);

        if (!$speaker) {
            return response()->json([
                'message' => 'Ponente no encontrado',
                'status' => 404
            ], 404);
        }

        // Verificar si el ponente está asignado a algún evento
        $isAssigned = Event::where('speaker_id', $id)->exists();

        if ($isAssigned) {
            return response()->json([
                'message' => 'No se puede eliminar el ponente porque está asignado a uno o más eventos.',
                'status' => 400
            ], 400);
        }

        // Eliminar la foto del ponente
        $photoPath = "/speakers/" . $speaker->photo;
        $uploadService::delete($photoPath);

        // Eliminar el ponente
        $speaker->delete();

        return response()->json([
            'message' => 'Ponente eliminado',
            'status' => 200
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $speaker = Speaker::find($id);

        if (!$speaker) {
            $data = [
                'message' => 'Ponente no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'photo' => 'required',
            'specialization' => 'required',
            'socialMedia' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $speaker->name = $request->name;
        $speaker->photo = $request->photo;
        $speaker->specialization = $request->specialization;
        $speaker->socialMedia = $request->socialMedia;

        $speaker->save();
        $data = [
            'message' => 'Ponente actualizado',
            'speaker' => $speaker,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartialSpeaker(Request $request, $id)
    {
        $speaker = Speaker::find($id);

        if (!$speaker) {
            return response()->json([
                'message' => 'Ponente no encontrado',
                'status' => 404
            ], 404);
        }

        $speaker->update($request->only('name', 'photo', 'specialization', 'socialMedia'));

        return response()->json([
            'message' => 'Ponente actualizado',
            'speaker' => $speaker,
            'status' => 200
        ], 200);
    }
}
