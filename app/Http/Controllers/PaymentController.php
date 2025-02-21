<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Email\TicketMail;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user')->get();

        if ($payments->isEmpty()) {
            $data = [
                'message' => 'No se han encontrado pagos',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'payments' => $payments,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(PaymentRequest $request)
    {
        if (!$request->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $request->messages(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'status' => $request->status
        ]);

        if (!$payment) {
            $data = [
                'message' => 'Error al crear el pago',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'payment' => $payment,
            'status' => 201
        ];

        $user = User::find($request->user_id);

        Mail::to($user->email)->send(new TicketMail($payment, $user));

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            $data = [
                'message' => 'Pago no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'payment' => $payment,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            $data = [
                'message' => 'Pago no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $payment->delete();

        $data = [
            'message' => 'Pago eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            $data = [
                'message' => 'Pago no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'quantity' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $payment->user_id = $request->user_id;
        $payment->quantity = $request->quantity;
        $payment->status = $request->status;

        $payment->save();
        $data = [
            'message' => 'Pago actualizado',
            'payment' => $payment,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartialSpeaker(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'message' => 'Pago no encontrado',
                'status' => 404
            ], 404);
        }

        $payment->update($request->only('user_id', 'quantity', 'status'));

        return response()->json([
            'message' => 'Pago actualizado',
            'payment' => $payment,
            'status' => 200
        ], 200);
    }
}
