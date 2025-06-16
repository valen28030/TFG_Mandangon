<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$usuario_id = $_GET['usuario_id']; 

// Consulta para obtener las listas
$sql_listas = "SELECT id_list, list_nom, list_desc, list_color FROM lista_compra WHERE list_usu = ?";
if ($stmt = $conn->prepare($sql_listas)) {
    $stmt->bind_param("i", $usuario_id);  // Usamos el ID del usuario
    $stmt->execute();
    $resultado_listas = $stmt->get_result();
    
    if ($resultado_listas->num_rows == 0) {
        die(json_encode(['error' => true, 'mensaje' => 'No se encontraron listas']));
    }

    $listas = [];
    while ($fila = $resultado_listas->fetch_assoc()) {
        $listas[] = [
            'id_list' => $fila['id_list'],
            'nombre' => $fila['list_nom'],
            'productos' => explode(", ", $fila['list_desc']),
            'color' => $fila['list_color'],
        ];
    }

    echo json_encode($listas); // Devuelve las listas en formato JSON
} else {
    die(json_encode(['error' => true, 'mensaje' => 'Error en la consulta de listas']));
}


// Cerrar conexión
$conn->close();
?>
