<script src="https://www.paypal.com/sdk/js?client-id=AXF5o8N4pjQaIY5kdIhP45wb0T5HmzV_DLumjrIMKhdOhFi3t4BY8CldnTRlpFrCg2uwkDEi7fa5Suwn&currency=EUR"></script>
<x-guest-layout>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4 text-white">Resumen de Inscripciones</h1>

        <!-- Contenedor donde se mostrarán las inscripciones -->
        <div id="inscripcionesContainer" class="space-y-4">
            <!-- Las inscripciones se cargarán aquí dinámicamente -->
        </div>

        <div id="paypal-button-container" class="mt-5"></div>
    </div>
    <div id="userData" class="hidden" data-user-id="{{ auth()->id() }}"></div>
    <input type="hidden" id="isStudent" value="{{ $isStudent }}">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const isStudent = document.getElementById('isStudent').value;
            const userId = document.getElementById('userData').getAttribute('data-user-id');

            fetch(`/api/registrations`)
                .then(response => response.json())
                .then(data => {
                    const inscripcionesContainer = document.getElementById("inscripcionesContainer");
                    inscripcionesContainer.innerHTML = ""; // Limpiar el contenedor de inscripciones

                    const registrations = data.registrations;
                    const inscripcionesDelUsuario = Object.values(registrations).flat().filter(inscripcion => inscripcion.user_id == userId);

                    if (inscripcionesDelUsuario.length === 0) {
                        inscripcionesContainer.innerHTML = "<p class='text-gray-500'>No has realizado ninguna inscripción.</p>";
                        return;
                    }

                    let totalCost = 0;
                    inscripcionesDelUsuario.forEach(inscripcion => {
                        const precio = isStudent == 1 ? 0 : (inscripcion.type === 'inPerson' ? 8 : 4);
                        totalCost += precio;

                        const inscripcionDiv = document.createElement("div");
                        inscripcionDiv.classList.add("bg-white", "p-4", "shadow-md", "rounded-md", "flex", "flex-col", "space-y-2");

                        inscripcionDiv.innerHTML = `
                        <h2 class="text-lg font-semibold">${inscripcion.event.name}</h2>
                        <p class="text-sm text-gray-600">Tipo de inscripción: ${inscripcion.type === 'inPerson' ? 'Presencial' : 'Virtual'}</p>
                        <p class="text-sm text-gray-600">Precio: €${precio}</p>
                    `;

                        inscripcionesContainer.appendChild(inscripcionDiv);
                    });

                    if (isStudent == 1) {
                        totalCost = 0;

                        const noPagoMensaje = document.createElement('p');
                        noPagoMensaje.classList.add('text-green-600', 'font-bold');
                        noPagoMensaje.innerText = "¡Eres estudiante! El coste de las inscripciones es gratuito.";

                        const botonDashboard = document.createElement('button');
                        botonDashboard.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'mt-2', 'rounded-md', 'hover:bg-blue-600');
                        botonDashboard.innerText = "Finalizar registro";
                        botonDashboard.addEventListener('click', function() {
                            const payment = {
                                user_id: userId,
                                quantity: totalCost,
                                status: "FREE"
                            };

                            fetch('/api/payments', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(payment)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status == 201) {
                                        alert("Pago creado correctamente");
                                        window.location.href = '/dashboard'; // Redirigir al dashboard
                                    } else {
                                        alert(data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error("Error al crear el pago:", error);
                                    alert("Error en la creación del pago");
                                });
                        });

                        inscripcionesContainer.appendChild(noPagoMensaje);
                        inscripcionesContainer.appendChild(botonDashboard);
                    }

                    // Si no es estudiante, mostrar el botón de PayPal
                    if (totalCost > 0) {
                        paypal.Buttons({
                            createOrder: function(data, actions) {
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: totalCost
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                actions.order.capture().then(function(detalles) {
                                    const payment = {
                                        user_id: userId,
                                        quantity: totalCost,
                                        status: "CORRECT"
                                    };

                                    fetch('/api/payments', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                            },
                                            body: JSON.stringify(payment)
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status == 201) {
                                                alert("Pago creado correctamente");
                                                window.location.href = '/dashboard'; // Redirigir al dashboard
                                            } else {
                                                alert(data.message);
                                            }
                                        })
                                        .catch(error => {
                                            console.error("Error al crear el pago:", error);
                                            alert("Error en la creación del pago");
                                        });
                                });
                            },
                            onCancel: function(data) {
                                alert('Pago cancelado');
                            }
                        }).render('#paypal-button-container');
                    }
                })
                .catch(error => console.error("Error al obtener las inscripciones:", error));
        });
    </script>
</x-guest-layout>