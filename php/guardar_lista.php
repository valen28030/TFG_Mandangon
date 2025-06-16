<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include('conexion.php'); 

// Capturar la entrada RAW y escribirla en el log
$rawInput = file_get_contents('php://input');
error_log("DEBUG: Raw input recibido: " . $rawInput);

// Decodificar el JSON de entrada
$data = json_decode($rawInput, true);
error_log("DEBUG: Datos decodificados: " . print_r($data, true));

// Verificar que se reciban todas las claves requeridas (omitimos id_list, ya que 0 es válido)
if (!isset($data['id_list'], $data['nombre'], $data['productos'], $data['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}   

$id_list = trim($data['id_list']);
$nombre = trim($data['nombre']);
$productos = $data['productos'];
$usuario_id = trim($data['usuario_id']);

error_log("DEBUG: id_list: " . $id_list);
error_log("DEBUG: nombre: " . $nombre);
error_log("DEBUG: usuario_id: " . $usuario_id);
error_log("DEBUG: productos (raw): " . print_r($productos, true));

// Validar que el nombre y usuario_id no estén vacíos
if ($nombre === "" || $usuario_id === "") {
    error_log("DEBUG: El nombre o usuario_id está vacío.");
    echo json_encode(["status" => "error", "message" => "Faltan datos importantes"]);
    exit;
}

// Convertir 'productos' a una cadena JSON si no es una cadena
if (!is_string($productos)) {
    $productos = json_encode($productos);
    error_log("DEBUG: productos convertido a cadena JSON: " . $productos);
}

$productos_decoded = json_decode($productos, true);
if (!is_array($productos_decoded)) {
    error_log("DEBUG: El formato de productos no es válido.");
    echo json_encode(["status" => "error", "message" => "Formato de productos inválido"]);
    exit;
}

$productos_str = implode(", ", $productos_decoded);
error_log("DEBUG: productos_str final: " . $productos_str);

global $conn; 

if ($id_list == "0") {
    $sql = "INSERT INTO lista_compra (list_nom, list_desc, list_usu) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $nombre, $productos_str, $usuario_id);
        if ($stmt->execute()) {
            error_log("DEBUG: Inserción exitosa. Nuevo id_list: " . $stmt->insert_id);
            echo json_encode([
                "status" => "success",
                "message" => "Lista guardada correctamente",
                "id_list" => $stmt->insert_id
            ]);
        } else {
            error_log("DEBUG: Error en la inserción: " . $stmt->error);
            echo json_encode(["status" => "error", "message" => "Error al guardar la lista"]);
        }
        $stmt->close();
    } else {
        error_log("DEBUG: Error al preparar la consulta de inserción: " . $conn->error);
        echo json_encode(["status" => "error", "message" => "Error al preparar la consulta"]);
    }
} else {
    $sql = "UPDATE lista_compra SET list_nom = ?, list_desc = ? WHERE id_list = ? AND list_usu = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssii", $nombre, $productos_str, $id_list, $usuario_id);
        if ($stmt->execute()) {
            error_log("DEBUG: Actualización exitosa para id_list: " . $id_list);
            echo json_encode(["status" => "success", "message" => "Lista actualizada correctamente"]);
        } else {
            error_log("DEBUG: Error en la actualización: " . $stmt->error);
            echo json_encode(["status" => "error", "message" => "Error al actualizar la lista"]);
        }
        $stmt->close();
    } else {
        error_log("DEBUG: Error al preparar la consulta de actualización: " . $conn->error);
        echo json_encode(["status" => "error", "message" => "Error al preparar la consulta"]);
    }
}
$conn->close();
?>
