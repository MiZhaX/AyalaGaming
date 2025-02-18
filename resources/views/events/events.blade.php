<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-white text-center">LISTA DE EVENTOS</h2>
            <h4 class="text-l font-bold text-white text-center">Todos los eventos duran 55 minutos</h4>
            <h4 class="text-l font-bold text-white mb-4 text-center">(A las 13h y las 17h se hará un descanso donde se ofrecerá un snack)</h4>
            <div id="loading" class="text-gray-400 text-center">Cargando eventos...</div>
            <div id="eventsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                <!-- Aquí se insertarán las tarjetas de eventos dinámicamente -->
            </div>
            <div id="errorMessage" class="text-red-500 text-center hidden mt-4">No se pudieron cargar los eventos.</div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/api/events')
                .then(response => response.json())
                .then(data => {
                    const loading = document.getElementById("loading");
                    const container = document.getElementById("eventsContainer");
                    const errorMessage = document.getElementById("errorMessage");

                    loading.style.display = "none"; // Ocultar mensaje de carga

                    if (data.events && data.events.length > 0) {
                        container.classList.remove("hidden");

                        data.events.forEach(event => {
                            // Obtenemos los datos del horario asociados con el evento
                            const schedule = event.schedule ? `<p class="text-gray-300"><strong>Horario:</strong> ${event.schedule.time}</p>` : `<p class="text-gray-300"><strong>Horario:</strong> No disponible</p>`;
                            const day = event.schedule ? `<p class="text-gray-300"><strong>Día:</strong> ${event.schedule.day}</p>` : `<p class="text-gray-300"><strong>Día:</strong> No disponible</p>`;

                            let card = `<div class="bg-gray-800 border-4 border-gray-600 shadow-lg rounded-lg p-6 text-white">
                                <h4 class="text-xl font-semibold mb-2">${event.name}</h4>
                                <p class="text-gray-300"><strong>Tipo:</strong> ${event.schedule.type}</p>
                                ${day}
                                ${schedule}
                                <p class="text-gray-300"><strong>Asistencia Presencial:</strong> ${event.inPersonAssistance}</p>
                                <p class="text-gray-300"><strong>Asistencia Virtual:</strong> ${event.virtualAssistance}</p>
                            </div>`;
                            container.innerHTML += card;
                        });
                    } else {
                        errorMessage.classList.remove("hidden");
                    }
                })
                .catch(error => {
                    console.error("Error al obtener eventos:", error);
                    document.getElementById("loading").style.display = "none";
                    document.getElementById("errorMessage").classList.remove("hidden");
                });
        });
    </script>
</x-app-layout>
