<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a AgroTech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function goToLogin() {
            window.location.href = "/login";
        }

        
    </script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-green-800 to-green-900 text-white p-6">

    <!-- Logo o Imagen representativa -->
    <img class="w-52 mb-8 rounded-xl shadow-2xl"
         src="https://images.unsplash.com/photo-1564078516393-cf04bd966897?auto=format&fit=crop&w=600&q=80"
         alt="AgroTech Agricultura Sostenible" />

    <!-- Título -->
    <h1 class="text-5xl font-extrabold text-white text-center">
        Bienvenido a AgroTech 🌿
    </h1>

    <!-- Descripción -->
    <p class="mt-5 text-lg text-gray-300 text-center max-w-2xl">
        En AgroTech, combinamos la innovación y la sostenibilidad para mejorar la producción agrícola.
        Descubre nuestras soluciones avanzadas para un futuro más verde.
    </p>

    <!-- Botones -->
    <div class="mt-10 flex flex-col sm:flex-row gap-4">
        <button onclick="goToLogin()"
            class="px-8 py-3 bg-blue-700 text-white text-lg font-medium rounded-xl shadow-md hover:bg-blue-800 transition">
            Acceder a la Plataforma
        </button>
    </div>

    <!-- Pie de página -->
    <footer class="mt-12 text-sm text-gray-500 text-center">
        © 2025 AgroTech S.L. Todos los derechos reservados.
    </footer>

</body>
</html>
