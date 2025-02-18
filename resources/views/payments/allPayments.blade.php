<x-app-layout>
    <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow md:max-w-3xl">
        <h1 class="text-2xl font-bold mb-4">Pagos Registrados</h1>

        <div id="paymentsContainer" class="space-y-4">
            <p class="text-gray-500">Cargando pagos...</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/payments')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const container = document.getElementById("paymentsContainer");
                    container.innerHTML = ""; // Limpiar contenido previo

                    if (!data.payments || data.payments.length === 0) {
                        container.innerHTML = "<p class='text-gray-500'>No hay pagos registrados.</p>";
                        return;
                    }

                    data.payments.forEach(payment => {
                        const paymentDiv = document.createElement("div");
                        paymentDiv.classList.add("bg-white", "p-4", "shadow", "rounded-md", "border");

                        paymentDiv.innerHTML = `
                            <h2 class="text-lg font-semibold">Usuario: ${payment.user.name} (ID: ${payment.user.id})</h2>
                            <p class="text-sm text-gray-600">Cantidad: $${payment.quantity}</p>
                            <p class="text-sm text-gray-600">Fecha: ${new Date(payment.created_at).toLocaleDateString()}</p>
                            <p class="text-sm text-gray-600">Status: ${payment.status}</p>
                        `;

                        container.appendChild(paymentDiv);
                    });
                })
                .catch(error => {
                    console.error("Error al obtener los pagos:", error);
                    document.getElementById("paymentsContainer").innerHTML = "<p class='text-red-500'>Error al cargar los pagos.</p>";
                });
        });
    </script>
</x-app-layout>
