<x-guest-layout>
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Completa tu registro</h2>
        <form action="{{ route('complete-isStudent.post') }}" method="POST">
            @csrf
            <label class="block mb-2">¿Eres estudiante?</label>
            <div class="flex items-center space-x-4">
                <label>
                    <input type="radio" name="is_student" value="1" required onchange="toggleCodeField(true)"> Sí
                </label>
                <label>
                    <input type="radio" name="is_student" value="0" required onchange="toggleCodeField(false)"> No
                </label>
            </div>

            <div id="studentCodeField" class="mt-4 hidden">
                <label for="student_code" class="block mb-2">Código de Verificación</label>
                <input type="text" name="student_code" id="student_code" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="mt-4 bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 border-2 border-black">
                Guardar y continuar
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-m text-black-600 hover:text-black-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Cerrar sesión') }}
            </button>
        </form>
    </div>
    @if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
    @endif

    <script>
        function toggleCodeField(isStudent) {
            document.getElementById('studentCodeField').classList.toggle('hidden', !isStudent);
            document.getElementById('student_code').required = isStudent;
        }
    </script>
</x-guest-layout>