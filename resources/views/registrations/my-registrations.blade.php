<x-app-layout>
    <div class="flex justify-center items-center mt-12">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-white w-full max-w-2xl">
            <h1 class="text-2xl font-bold mb-4 text-center">Mis Inscripciones</h1>

            <!-- Contenedor donde se mostrarán las inscripciones -->
            <div id="inscripcionesContainer" class="space-y-4 text-center">
                <p class="text-gray-300">Cargando inscripciones...</p>
            </div>
        </div>
    </div>

    <!-- Guardar el ID del usuario en un campo oculto para acceder desde JS -->
    <div id="userData" class="hidden" data-user-id="{{ auth()->id() }}"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userId = document.getElementById('userData').getAttribute('data-user-id');

            fetch('/api/registrations')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const inscripcionesContainer = document.getElementById("inscripcionesContainer");
                    inscripcionesContainer.innerHTML = ""; // Limpiar el contenedor

                    const inscripcionesDelUsuario = Object.values(data.registrations).flat().filter(inscripcion => inscripcion.user_id == userId);

                    if (inscripcionesDelUsuario.length === 0) {
                        inscripcionesContainer.innerHTML = "<p class='text-gray-300'>No tienes inscripciones.</p>";
                        return;
                    }

                    inscripcionesDelUsuario.forEach(inscripcion => {
                        const inscripcionDiv = document.createElement("div");
                        inscripcionDiv.classList.add("bg-white", "p-4", "shadow-md", "rounded-md", "flex", "flex-col", "space-y-2", "text-black");

                        inscripcionDiv.innerHTML = `
                            <h2 class="text-lg font-semibold">${inscripcion.event.name}</h2>
                            <p class="text-sm text-gray-600">Fecha: ${inscripcion.event.schedule.day} - ${inscripcion.event.schedule.time}</p>
                            <p class="text-sm text-gray-600">Tipo: ${inscripcion.type === 'inPerson' ? 'Presencial' : 'Virtual'}</p>
                        `;

                        inscripcionesContainer.appendChild(inscripcionDiv);
                    });
                })
                .catch(error => {
                    console.error("Error al obtener las inscripciones:", error);
                    document.getElementById("inscripcionesContainer").innerHTML = "<p class='text-red-500'>Error al cargar las inscripciones.</p>";
                });
        });
    </script>
</x-app-layout>
