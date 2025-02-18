<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Speakers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Lista de Ponentes</h3>
                    <div id="speakers-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Aquí se insertarán los ponentes con JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('/api/speakers')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.speakers && data.speakers.length > 0) {
                        const speakersList = document.getElementById("speakers-list");
                        data.speakers.forEach(speaker => {
                            const speakerCard = `
                                <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg shadow-lg">
                                    <img src="/storage/speakers/${speaker.photo}" alt="${speaker.name}" class="w-full h-40 rounded-lg mb-4">
                                    <h4 class="text-lg font-bold">${speaker.name}</h4>
                                    <p class="text-sm text-gray-500">${speaker.specialization}</p>
                                    <div class="mt-3 flex space-x-3">
                                        ${speaker.socialMedia.map(social => `
                                            <a href="${social.url}" target="_blank" class="text-blue-500 hover:text-blue-700">${social.platform}</a>
                                        `).join("&nbsp;&nbsp;&nbsp;")}
                                    </div>
                                </div>
                            `;
                            speakersList.innerHTML += speakerCard;
                        });
                    } else {
                        document.getElementById("speakers-list").innerHTML = `<p class="text-red-500">No hay ponentes disponibles.</p>`;
                    }
                })
                .catch(error => {
                    console.error("Error obteniendo los ponentes:", error);
                    document.getElementById("speakers-list").innerHTML = `<p class="text-red-500">Error cargando los ponentes.</p>`;
                });
        });
    </script>
</x-app-layout>
