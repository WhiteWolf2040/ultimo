<?php
// Configura el servidor para usar el puerto dinámico asignado por Railway
$port = $_ENV['PORT'] ?? 8080;  // Usa el puerto asignado por Railway o el 8080 por defecto
$server = "0.0.0.0";  // Escucha en todas las interfaces de red disponibles

// Comando para ejecutar el servidor PHP en el puerto dinámico
$command = "php -S $server:$port";
exec($command);
