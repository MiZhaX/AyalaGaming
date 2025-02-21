<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Lista de Eventos</h3>

                    <!-- Botón para crear nuevo evento -->
                    <button id="createEventButton" class="mb-4 bg-blue-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-300">
                        Crear Evento
                    </button>

                    <!-- Formulario para crear nuevo evento -->
                    <div id="createEventForm" class="hidden mb-4">
                        <h4 class="font-bold text-xl mb-4">Crear Nuevo Evento</h4>
                        <form id="eventForm" class="space-y-4">
                            <div class="mb-3">
                                <label for="eventName" class="block text-gray-700 dark:text-gray-300">Nombre</label>
                                <input type="text" id="eventName" name="name" class="form-input w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                            </div>
                            <div class="mb-3">
                                <label for="eventType" class="block text-gray-700 dark:text-gray-300">Tipo</label>
                                <select id="eventType" name="type" class="form-select w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                                    <option value="Conference">Conferencia</option>
                                    <option value="Workshop">Taller</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="eventDay" class="block text-gray-700 dark:text-gray-300">Día</label>
                                <select id="eventDay" name="day" class="form-select w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                                    <option value="Thursday">Jueves</option>
                                    <option value="Friday">Viernes</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="eventSchedule" class="block text-gray-700 dark:text-gray-300">Horario</label>
                                <select id="eventSchedule" name="schedule" class="form-select w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                                    <!-- Los horarios disponibles se cargarán aquí -->
                                </select>
                            </div>
                            <!-- Campo para seleccionar el Speaker -->
                            <div class="mb-3">
                                <label for="speakerId" class="block text-gray-700 dark:text-gray-300">Speaker</label>
                                <select id="speakerId" name="speaker_id" class="form-select w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                                    <!-- Aquí se llenarán los speakers -->
                                </select>
                            </div>
                            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                                Guardar Evento
                            </button>
                        </form>
                    </div>

                    <!-- Mensaje de carga y error -->
                    <div id="loading" class="text-gray-400">Cargando eventos...</div>
                    <table class="table-auto w-full border-collapse border border-gray-500 hidden" id="eventsTable">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-4 py-2 border border-gray-400">Nombre</th>
                                <th class="px-4 py-2 border border-gray-400">Tipo</th>
                                <th class="px-4 py-2 border border-gray-400">Día</th>
                                <th class="px-4 py-2 border border-gray-400">Horario</th>
                                <th class="px-4 py-2 border border-gray-400">Presencial</th>
                                <th class="px-4 py-2 border border-gray-400">Online</th>
                                <th class="px-4 py-2 border border-gray-400">Speaker</th>
                                <th class="px-4 py-2 border border-gray-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="eventsBody">
                            <!-- Aquí se insertarán los eventos dinámicamente -->
                        </tbody>
                    </table>
                    <div id="errorMessage" class="text-red-500 hidden mt-4">No se pudieron cargar los eventos.</div>

                    <!-- Formulario de edición (inicialmente oculto) -->
                    <div id="editEventModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg" style="width: 25rem;">
                            <h3 class="text-xl font-bold text-black mb-4">Editar Evento</h3>
                            <form id="editEventForm">
                                <div class="mb-4">
                                    <label for="editName" class="block text-sm font-medium text-black">Nombre del Evento</label>
                                    <input type="text" id="editName" class="w-full px-4 py-2 border rounded-md text-black" required>
                                </div>
                                <div class="mb-4">
                                    <label for="editType" class="block text-sm font-medium text-black">Tipo</label>
                                    <select id="editType" class="w-full px-4 py-2 border rounded-md text-black" required disabled>
                                        <option value="Conference">Conference</option>
                                        <option value="Workshop">Workshop</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="editDay" class="block text-sm font-medium text-black">Día</label>
                                    <select id="editDay" class="w-full px-4 py-2 border rounded-md text-black" required>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="editSchedule" class="block text-sm font-medium text-black">Horario</label>
                                    <input type="time" id="editSchedule" class="w-full px-4 py-2 border rounded-md text-black" required>
                                </div>
                                <div class="mb-4">
                                    <label for="editSpeaker" class="block text-sm font-medium text-black">Speaker</label>
                                    <select id="editSpeaker" class="w-full px-4 py-2 border rounded-md text-black" required>
                                        <!-- Los speakers se llenarán dinámicamente -->
                                    </select>
                                </div>
                                <div class="mb-4 text-right">
                                    <button type="submit" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300">Actualizar Evento</button>
                                    <button type="button" id="closeEditModal" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loading = document.getElementById("loading");
            const table = document.getElementById("eventsTable");
            const tbody = document.getElementById("eventsBody");
            const errorMessage = document.getElementById("errorMessage");
            const createEventButton = document.getElementById("createEventButton");
            const createEventForm = document.getElementById("createEventForm");
            const eventForm = document.getElementById("eventForm");
            const speakerSelect = document.getElementById("speakerId");
            const eventDaySelect = document.getElementById("eventDay");
            const eventScheduleSelect = document.getElementById("eventSchedule");
            const eventTypeSelect = document.getElementById("eventType");
            const editSpeaker = document.getElementById('editSpeaker');

            let currentEventId = null; // Variable para almacenar el ID del evento actual

            // Enviar el formulario de creación de evento
            eventForm.addEventListener("submit", function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

                const newEvent = {
                    name: document.getElementById("eventName").value,
                    type: document.getElementById("eventType").value,
                    day: document.getElementById("eventDay").value,
                    time: document.getElementById("eventSchedule").value,
                    speaker_id: document.getElementById("speakerId").value
                };

                console.log(newEvent);

                // Enviar la solicitud POST a la API
                fetch('/api/events', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(newEvent)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == 201) {
                            alert("Evento creado correctamente");
                            location.reload(); // Recargar la página para reflejar el nuevo evento
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error al crear el evento:", error);
                        alert("Error en la creación del evento");
                    });
            });

            // Mostrar/ocultar formulario de creación
            createEventButton.addEventListener("click", () => {
                createEventForm.classList.toggle("hidden");
            });

            // Cargar speakers
            fetch('/api/speakers')
                .then(response => response.json())
                .then(data => {
                    if (data.speakers && data.speakers.length > 0) {
                        data.speakers.forEach(speaker => {
                            const option = document.createElement("option");
                            option.value = speaker.id;
                            option.textContent = speaker.name;
                            speakerSelect.appendChild(option);
                        });
                        data.speakers.forEach(speaker => {
                            const optionEdit = document.createElement("option");
                            optionEdit.value = speaker.id;
                            optionEdit.textContent = speaker.name;
                            editSpeaker.appendChild(optionEdit);
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al obtener speakers:", error);
                });

            // Actualizar horarios disponibles según el día y tipo de evento
            function updateAvailableSchedules() {
                const selectedDay = eventDaySelect.value;
                const selectedType = eventTypeSelect.value;

                // Hacer la solicitud a la API para obtener los horarios disponibles
                fetch(`/api/available-schedules?day=${selectedDay}&type=${selectedType}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const schedules = data.schedules || [];

                            eventScheduleSelect.innerHTML = ""; // Limpiar el select de horarios

                            // Si hay horarios disponibles, agregar las opciones al select
                            if (schedules.length > 0) {
                                schedules.forEach(schedule => {
                                    const option = document.createElement("option");
                                    option.value = schedule;
                                    option.textContent = schedule;
                                    eventScheduleSelect.appendChild(option);
                                });
                            } else {
                                // Si no hay horarios, mostrar un mensaje
                                const option = document.createElement("option");
                                option.value = "";
                                option.textContent = "No hay horarios disponibles";
                                eventScheduleSelect.appendChild(option);
                            }
                        } else {
                            console.error("No se pudieron obtener los horarios.");
                        }
                    })
                    .catch(error => {
                        console.error("Error al obtener los horarios:", error);
                    });
            }

            // Llamar a la función para inicializar los horarios disponibles al cargar la página
            updateAvailableSchedules();

            // Escuchar el cambio de día o tipo de evento para actualizar los horarios disponibles
            eventDaySelect.addEventListener("change", updateAvailableSchedules);
            eventTypeSelect.addEventListener("change", updateAvailableSchedules);

            // Cargar eventos
            fetch('/api/events')
                .then(response => response.json())
                .then(data => {
                    loading.style.display = "none"; // Ocultar mensaje de carga
                    if (data.events && data.events.length > 0) {
                        table.classList.remove("hidden");

                        data.events.forEach(event => {
                            let row = `<tr class="border border-gray-400">
                        <td class="text-center px-4 py-2">${event.name}</td>
                        <td class="text-center px-4 py-2">${event.schedule.type}</td>
                        <td class="text-center px-4 py-2">${event.schedule.day}</td>
                        <td class="text-center px-4 py-2">${event.schedule.time}</td>
                        <td class="text-center px-4 py-2">${event.inPersonAssistance}</td>
                        <td class="text-center px-4 py-2">${event.virtualAssistance}</td>
                        <td class="text-center px-4 py-2">${event.speaker_id}</td>
                        <td class="text-center px-4 py-2">
                            <button data-id="${event.id}" class="deleteButton bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Eliminar</button>
                        </td>
                    </tr>`;
                            tbody.innerHTML += row;
                        });

                        // Editar evento
                        document.querySelectorAll('.editButton').forEach(button => {
                            button.addEventListener('click', (event) => {
                                const eventId = event.target.dataset.id;
                                currentEventId = eventId; // Guardar el ID del evento en la variable
                                // Cargar evento para edición
                                fetch(`/api/events/${eventId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.event) {
                                            console.log(data.event);
                                            document.getElementById('editName').value = data.event.name;
                                            document.getElementById('editType').value = data.event.schedule.event_type;
                                            document.getElementById('editDay').value = data.event.schedule.day;
                                            document.getElementById('editSchedule').value = data.event.schedule.time;
                                            document.getElementById('editSpeaker').value = data.event.speaker_id;
                                            document.getElementById('editEventModal').classList.remove("hidden");
                                        }
                                    });
                            });
                        });

                        // Eliminar evento
                        document.querySelectorAll('.deleteButton').forEach(button => {
                            button.addEventListener('click', (event) => {
                                const eventId = event.target.dataset.id;
                                if (confirm("¿Seguro que quieres eliminar este evento?")) {
                                    fetch(`/api/events/${eventId}`, {
                                            method: 'DELETE',
                                        }).then(response => response.json())
                                        .then(data => {
                                            if (data.status == 200) {
                                                alert('Evento eliminado');
                                                location.reload(); // Recargar la página para reflejar cambios
                                            }
                                            else{
                                                alert(data.message);
                                            }
                                        });
                                }
                            });
                        });
                    } else {
                        errorMessage.style.display = "block"; // Mostrar mensaje de error
                    }
                })
                .catch(error => {
                    console.error("Error al obtener los eventos:", error);
                    errorMessage.style.display = "block"; // Mostrar mensaje de error
                });

            // Formulario de edición de evento
            document.getElementById('editEventForm').addEventListener('submit', function(event) {
                event.preventDefault();

                if (!currentEventId) {
                    console.error("Event ID is missing.");
                    return;
                }

                const updatedEvent = {
                    name: document.getElementById('editName').value,
                    type: document.getElementById('editType').value,
                    day: document.getElementById('editDay').value,
                    time: document.getElementById('editSchedule').value,
                    speaker_id: document.getElementById('editSpeaker').value
                };
                console.log(updatedEvent);

                fetch(`/api/events/${currentEventId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(updatedEvent),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == 200) {
                            alert('Evento actualizado');
                            document.getElementById('editEventModal').classList.add("hidden");
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            });

            // Cerrar modal de edición
            document.getElementById('closeEditModal').addEventListener('click', function() {
                document.getElementById('editEventModal').classList.add("hidden");
            });
        });
    </script>
</x-app-layout>