<x-guest-layout>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4 text-white">Inscripción a Eventos</h1>
        <div id="eventosContainer" class="space-y-4">
            <!-- Los eventos se cargarán dinámicamente aquí -->
        </div>
        <form method="POST" action="{{ route('complete-payment') }}">
            @csrf
            <input type="hidden" name="is_student" value="{{ auth()->user()->is_student }}">
            <button id="continuarPago" class="bg-green-500 text-white px-6 py-2 mt-2 rounded-md hover:bg-green-600">
                Continuar al Pago
            </button>
        </form>
    </div>
    <div id="userData" class="hidden" data-user-id="{{ auth()->id() }}"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/events')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById("eventosContainer");
                    container.innerHTML = "";

                    if (data.length === 0) {
                        container.innerHTML = "<p class='text-gray-500'>No hay eventos disponibles.</p>";
                        return;
                    }

                    data.events.forEach(evento => {
                        console.log(evento);
                        const eventoDiv = document.createElement("div");
                        eventoDiv.classList.add("bg-white", "p-4", "shadow-md", "rounded-md", "flex", "flex-col", "space-y-2");

                        eventoDiv.innerHTML = `
                            <div class="flex flex-row space-y-2 justify-between items-center">
                                <div class="flex flex-col">
                                    <h2 class="text-lg font-semibold">${evento.name}</h2>
                                    <p class="text-sm text-gray-600">${evento.schedule.type}</p>
                                    <p class="text-sm text-gray-600">${evento.schedule.day} - ${evento.schedule.time}</p>
                                </div>
                                <form onsubmit="inscribirse(${evento.id}, event, this)" class="flex align-center gap-1">
                                    <select id="tipoInscripcion" class="border px-4 py-2 rounded-md">
                                        <option value="inPerson">Presencial</option>
                                        <option value="virtual">Virtual</option>
                                    </select>
                                    <button type="submit" id="inscribirBtn_${evento.id}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        Inscribirse
                                    </button>
                                </form>
                            </div>
                        `;

                        container.appendChild(eventoDiv);
                    });
                })
                .catch(error => console.error("Error al obtener los eventos:", error));
        });

        function inscribirse(eventoId, event, form) {
            event.preventDefault();

            const tipoInscripcion = form.querySelector("#tipoInscripcion").value;
            const userId = document.getElementById('userData').getAttribute('data-user-id');

            const registrateEvent = {
                event_id: eventoId,
                user_id: userId,
                type: tipoInscripcion
            };

            console.log(registrateEvent);

            fetch(`/api/registrations`, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(registrateEvent)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 201) {
                        alert("Inscripción exitosa.");
                        // Deshabilitar el botón de inscripción y cambiar el texto
                        const btn = form.querySelector("button");
                        const select = form.querySelector("select");

                        btn.disabled = true;
                        btn.innerText = "Inscrito";
                        btn.style.backgroundColor = "#2d3748";
                        select.disabled = true;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error("Error al inscribirse:", error));
        }
    </script>
</x-guest-layout>
