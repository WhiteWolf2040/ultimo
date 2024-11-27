<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET"); // Ahora permite POST y GET
header("Content-Type: application/json");
$servername = "junction.proxy.rlwy.net"; 
$username = "root";  
$password = "ocOkOlRjOdZRtDCiwwFXKsvSXfNsJqTR"; 
$dbname = "railway";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}
// Verificación de conexión con una ruta GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SHOW TABLES";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Conexión exitosa a la base de datos",
            "tables" => $result->fetch_all(MYSQLI_ASSOC)
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al conectarse a la base de datos: " . $conn->error
        ]);
    }
    exit;
}
// Ruta POST (ya existente para manejar órdenes)
$inputData = file_get_contents("php://input");
if (empty($inputData)) {
    echo json_encode(["status" => "error", "message" => "No se recibieron datos."]);
    exit;
}
$data = json_decode($inputData, true);
if ($data === null) {
    echo json_encode(["status" => "error", "message" => "Error al decodificar el JSON"]);
    exit;
}
if (isset($data['orders']) && is_array($data['orders'])) {
    foreach ($data['orders'] as $order) {
        $id_usuario = $conn->real_escape_string($order['id_usuario']);
        $id_producto = $conn->real_escape_string($order['id_producto']);
        $cantidad = $conn->real_escape_string($order['cantidad']);
        $sql = "INSERT INTO productosxusuario (id_usuario, id_producto, cantidad) 
                VALUES ('$id_usuario', '$id_producto', '$cantidad')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Nueva orden registrada correctamente"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al registrar la orden: " . $conn->error]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos de orden no válidos"]);
}
$conn->close();
?>



