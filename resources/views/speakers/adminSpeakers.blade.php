<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> {{ __('Ponentes') }} </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Lista de Ponentes</h3>

                    <!-- Botón para crear nuevo ponente -->
                    <button id="createSpeakerButton" class="mb-4 bg-blue-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-300">
                        Crear Ponente
                    </button>

                    <!-- Formulario para crear nuevo ponente -->
                    <div id="createSpeakerForm" class="hidden mb-4">
                        <h4 class="font-bold text-xl mb-4" enctype="multipart/form-data">Crear Nuevo Ponente</h4>
                        <form id="speakerForm" class="space-y-4">
                            <div class="mb-3">
                                <label for="speakerName" class="block text-gray-700 dark:text-gray-300">Nombre</label>
                                <input type="text" id="speakerName" name="name" class="form-input w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                            </div>
                            <div class="mb-3">
                                <label for="speakerPhoto" class="block text-gray-700 dark:text-gray-300">Foto</label>
                                <input type="file" id="speakerPhoto" name="photo"
                                    class="form-input w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200"
                                    accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label for="speakerSpecialization" class="block text-gray-700 dark:text-gray-300">Especialización</label>
                                <input type="text" id="speakerSpecialization" name="specialization" class="form-input w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required>
                            </div>
                            <div class="mb-3">
                                <label for="speakerSocialMedia" class="block text-gray-700 dark:text-gray-300">Redes Sociales (RRSS: URL)</label>
                                <textarea id="speakerSocialMedia" name="socialMedia" class="form-textarea w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-200" required placeholder="Instagram: Enlace"></textarea>
                            </div>
                            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                                Guardar Ponente
                            </button>
                        </form>
                    </div>

                    <!-- Mensaje de carga y error -->
                    <div id="loadingSpeakers" class="text-gray-400">Cargando ponentes...</div>
                    <table class="table-auto w-full border-collapse border border-gray-500 hidden" id="speakersTable">
                        <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="px-4 py-2 border border-gray-400">Nombre</th>
                                <th class="px-4 py-2 border border-gray-400">Foto</th>
                                <th class="px-4 py-2 border border-gray-400">Especialización</th>
                                <th class="px-4 py-2 border border-gray-400">Redes Sociales</th>
                                <th class="px-4 py-2 border border-gray-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="speakersBody">
                            <!-- Aquí se insertarán los ponentes dinámicamente -->
                        </tbody>
                    </table>
                    <div id="errorMessageSpeakers" class="text-red-500 hidden mt-4">No se pudieron cargar los ponentes.</div>

                    <!-- Formulario de edición (inicialmente oculto) -->
                    <div id="editSpeakerModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg" style="width: 25rem;">
                            <h3 class="text-xl font-bold text-black mb-4">Editar Ponente</h3>
                            <form id="editSpeakerForm">
                                <div class="mb-4">
                                    <label for="editSpeakerName" class="block text-sm font-medium text-black">Nombre del Ponente</label>
                                    <input type="text" id="editSpeakerName" class="w-full px-4 py-2 border rounded-md text-black" required>
                                </div>
                                <div class="mb-4 hidden">
                                    <label for="editSpeakerPhoto" class="block text-sm font-medium text-black">Foto URL</label>
                                    <input type="text" id="editSpeakerPhoto" class="w-full px-4 py-2 border rounded-md text-black" required>
                                </div>
                                <div class="mb-4">
                                    <label for="editSpeakerSpecialization" class="block text-sm font-medium text-black">Especialización</label>
                                    <input type="text" id="editSpeakerSpecialization" class="w-full px-4 py-2 border rounded-md text-black" required>
                                </div>
                                <div class="mb-4">
                                    <label for="editSpeakerSocialMedia" class="block text-sm font-medium text-black">Redes Sociales (RRSS: URL)</label>
                                    <textarea id="editSpeakerSocialMedia" class="form-textarea w-full p-2 border rounded-md text-black" required placeholder="Instagram: Enlace \n Twitter: Enlace"></textarea>
                                </div>
                                <div class="mb-4 text-right">
                                    <button type="submit" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300">Actualizar Ponente</button>
                                    <button type="button" id="closeEditSpeakerModal" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-300">Cancelar</button>
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
            const loadingSpeakers = document.getElementById("loadingSpeakers");
            const speakersTable = document.getElementById("speakersTable");
            const speakersBody = document.getElementById("speakersBody");
            const errorMessageSpeakers = document.getElementById("errorMessageSpeakers");
            const createSpeakerButton = document.getElementById("createSpeakerButton");
            const createSpeakerForm = document.getElementById("createSpeakerForm");
            const speakerForm = document.getElementById("speakerForm");
            let currentSpeakerId = null;

            // Enviar el formulario de creación de ponente
            speakerForm.addEventListener("submit", function(event) {
                event.preventDefault();

                const formData = new FormData();
                formData.append("name", document.getElementById("speakerName").value);
                formData.append("specialization", document.getElementById("speakerSpecialization").value);
                formData.append("photo", document.getElementById("speakerPhoto").files[0]);
                const socialMediaInput = document.getElementById("speakerSocialMedia").value;

                // Procesar social media
                const socialMedia = socialMediaInput.split("\n").map(entry => {
                    const parts = entry.split(": ");
                    if (parts.length === 2) {
                        return {
                            platform: parts[0].trim(),
                            url: parts[1].trim()
                        };
                    }
                    return null;
                }).filter(item => item !== null); // Filtra valores inválidos

                formData.append("socialMedia", JSON.stringify(socialMedia));

                console.log(formData);

                fetch('/api/speakers', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.status == 201) {
                            alert("Ponente creado correctamente");
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error al crear el ponente:", error);
                        alert("Error en la creación del ponente");
                    });
            });

            // Mostrar/ocultar formulario de creación
            createSpeakerButton.addEventListener("click", () => {
                createSpeakerForm.classList.toggle("hidden");
            });

            // Cargar ponentes
            fetch('/api/speakers')
                .then(response => response.json())
                .then(data => {
                    loadingSpeakers.style.display = "none";
                    if (data.speakers && data.speakers.length > 0) {
                        speakersTable.classList.remove("hidden");
                        data.speakers.forEach(speaker => {
                            console.log(speaker);

                            // Crear una cadena para las redes sociales
                            let socialMediaLinks = '';
                            if (speaker.socialMedia && Array.isArray(speaker.socialMedia)) {
                                speaker.socialMedia.forEach(media => {
                                    socialMediaLinks += `<a href="${media.url}" target="_blank" class="text-blue-500 hover:underline">${media.platform}</a><br>`;
                                });
                            }

                            let row = `<tr class="border border-gray-400">
                                <td class="text-center px-4 py-2">${speaker.name}</td>
                                <td class="text-center px-4 py-2"> <img src="/storage/speakers/${speaker.photo}" alt="${speaker.name}" class="w-16 h-16 object-cover rounded-full"></td>
                                <td class="text-center px-4 py-2">${speaker.specialization}</td>
                                <td class="text-center px-4 py-2">${socialMediaLinks}</td>
                                <td class="text-center px-4 py-2">
                                    <button data-id="${speaker.id}" class="editSpeakerButton bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300">Editar</button>
                                    <button data-id="${speaker.id}" class="deleteSpeakerButton bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Eliminar</button>
                                </td>
                            </tr>`;

                            speakersBody.innerHTML += row;
                        });

                        // Editar ponente
                        document.querySelectorAll('.editSpeakerButton').forEach(button => {
                            button.addEventListener('click', (event) => {
                                const speakerId = event.target.dataset.id;
                                currentSpeakerId = speakerId;
                                // Cargar ponente para edición
                                fetch(`/api/speakers/${speakerId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.speaker) {
                                            document.getElementById('editSpeakerName').value = data.speaker.name;
                                            document.getElementById('editSpeakerPhoto').value = data.speaker.photo;
                                            document.getElementById('editSpeakerSpecialization').value = data.speaker.specialization;

                                            // Convertir JSON de socialMedia a formato "Plataforma: URL"
                                            const socialMediaText = data.speaker.socialMedia
                                                .map(entry => `${entry.platform}: ${entry.url}`)
                                                .join("\n");

                                            document.getElementById('editSpeakerSocialMedia').value = socialMediaText;

                                            document.getElementById('editSpeakerModal').classList.remove("hidden");
                                        }
                                    });
                            });
                        });

                        // Eliminar ponente
                        document.querySelectorAll('.deleteSpeakerButton').forEach(button => {
                            button.addEventListener('click', (event) => {
                                const speakerId = event.target.dataset.id;
                                console.log(speakerId);
                                if (confirm("¿Seguro que quieres eliminar este ponente?")) {
                                    fetch(`/api/speakers/${speakerId}`, {
                                            method: 'DELETE',
                                        }).then(response => response.json())
                                        .then(data => {
                                            if (data.status == 200) {
                                                alert('Ponente eliminado');
                                                location.reload();
                                            }
                                        });
                                }
                            });
                        });
                    } else {
                        errorMessageSpeakers.style.display = "block";
                    }
                })
                .catch(error => {
                    console.error("Error al obtener los ponentes:", error);
                    errorMessageSpeakers.style.display = "block";
                });

            // Formulario de edición de ponente
            document.getElementById('editSpeakerForm').addEventListener('submit', function(event) {
                event.preventDefault();

                if (!currentSpeakerId) {
                    console.error("Speaker ID is missing.");
                    return;
                }

                const socialMediaInput = document.getElementById("editSpeakerSocialMedia").value;

                // Procesar social media
                const socialMedia = socialMediaInput.split("\n").map(entry => {
                    const parts = entry.split(": ");
                    if (parts.length === 2) {
                        return {
                            platform: parts[0].trim(),
                            url: parts[1].trim()
                        };
                    }
                    return null;
                }).filter(item => item !== null); // Filtra valores inválidos                

                const updatedSpeaker = {
                    name: document.getElementById('editSpeakerName').value,
                    photo: document.getElementById('editSpeakerPhoto').value,
                    specialization: document.getElementById('editSpeakerSpecialization').value,
                    socialMedia: socialMedia
                };
                console.log(updatedSpeaker);

                fetch(`/api/speakers/${currentSpeakerId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(updatedSpeaker),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.status == 200) {
                            alert('Ponente actualizado');
                            document.getElementById('editSpeakerModal').classList.add("hidden");
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            });

            // Cerrar modal de edición
            document.getElementById('closeEditSpeakerModal').addEventListener('click', function() {
                document.getElementById('editSpeakerModal').classList.add("hidden");
            });
        });
    </script>
</x-app-layout>