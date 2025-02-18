<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket de Acceso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 600px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        .ticket-info {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
        .ticket-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h2>¡Hola, {{ $user->name }}!</h2>
    <p>Gracias por tu pago. Este es tu ticket de acceso a las jornadas.</p>

    <div class="ticket-info">
        <p><strong>Número de Ticket:</strong> {{ $payment->id }}</p>
        <p><strong>Fecha de Pago:</strong> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Monto Pagado:</strong> ${{ $payment->quantity }}</p>
        <p><strong>Estado del pago:</strong> {{ $payment->status }}</p>
    </div>

    <p>Guarda este correo, ya que servirá como comprobante de pago y validación de acceso.</p>
    <p>¡Nos vemos en las jornadas!</p>
</body>
</html>
