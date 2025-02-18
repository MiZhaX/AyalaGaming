<x-app-layout>
    <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow md:max-w-3xl">
        <h1 class="text-2xl font-bold mb-4">Inscripciones de Usuarios</h1>

        <div id="inscriptionsContainer" class="space-y-6">
            <p class="text-gray-500">Cargando inscripciones...</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/registrations')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const container = document.getElementById("inscriptionsContainer");
                    container.innerHTML = ""; // Limpiar contenido previo

                    if (!data.registrations || Object.keys(data.registrations).length === 0) {
                        container.innerHTML = "<p class='text-gray-500'>No hay inscripciones registradas.</p>";
                        return;
                    }

                    Object.entries(data.registrations).forEach(([userId, inscripciones]) => {
                        const userSection = document.createElement("div");
                        userSection.classList.add("border", "p-4", "rounded-lg", "shadow-md", "bg-gray-100");

                        userSection.innerHTML = `
                            <h2 class="text-lg font-semibold mb-2">Usuario ID: ${userId}</h2>
                            <div class="space-y-3"></div>
                        `;

                        const userInscriptionContainer = userSection.querySelector(".space-y-3");

                        inscripciones.forEach(inscripcion => {
                            const inscripcionDiv = document.createElement("div");
                            inscripcionDiv.classList.add("bg-white", "p-4", "shadow", "rounded-md");

                            inscripcionDiv.innerHTML = `
                                <h3 class="text-md font-semibold">${inscripcion.event.name}</h3>
                                <p class="text-sm text-gray-600">Fecha: ${inscripcion.event.schedule.day} - ${inscripcion.event.schedule.time}</p>
                                <p class="text-sm text-gray-600">Tipo: ${inscripcion.type === 'inPerson' ? 'Presencial' : 'Virtual'}</p>
                            `;

                            userInscriptionContainer.appendChild(inscripcionDiv);
                        });

                        container.appendChild(userSection);
                    });
                })
                .catch(error => {
                    console.error("Error al obtener las inscripciones:", error);
                    document.getElementById("inscriptionsContainer").innerHTML = "<p class='text-red-500'>Error al cargar las inscripciones.</p>";
                });
        });
    </script>
</x-app-layout>
