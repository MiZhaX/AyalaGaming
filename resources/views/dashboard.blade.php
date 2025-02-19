<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bienvenido a tu Dashboard personal de las jornadas Ayala Gamming. Aquí podrás ver todos los eventos que se estarán celebrando en las jornadas. 
                        Además de ver y conocer un poco más a los ponentes que estarán realizando los diferentes eventos.") }} <br>
                    {{ __("También puedes ver los eventos a los que te has inscrito y el coste total de las inscripciones.") }}
                </div>
                <img src="https://www.erasmusdays.eu/wp-content/uploads/2024/07/phpFFpJqz.jpg" alt="logo_ayala" >
            </div>
        </div>
    </div>
</x-app-layout>
