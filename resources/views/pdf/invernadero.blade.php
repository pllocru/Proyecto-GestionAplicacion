<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Invernadero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .contenedor {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .detalle {
            margin-bottom: 10px;
        }
        .detalle strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Detalles del Invernadero</h1>
        <div class="detalle"><strong> Nombre del Invernadero:</strong> {{ $data['nombre'] }}</div>
        <div class="detalle"><strong> Número de veces recolectado:</strong> {{ $data['recolectado'] }}</div>
        <div class="detalle"><strong> Productor:</strong> {{ $data['productor'] }}</div>
        <div class="detalle"><strong> Parcela:</strong> {{ $data['parcela'] }}</div>
        <div class="detalle"><strong> Explotación:</strong> {{ $data['explotacion'] }}</div>

        <h3> Fechas de Recolección</h3>
        <ul>
            @foreach ($data['fechas'] as $fecha)
                <li>{{ $fecha }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
