<?php
include("conexion.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Verifica si se han enviado los parámetros necesarios
if (isset($_POST['usuarioId']) && isset($_POST['restauranteId']) && isset($_POST['descripcion']) && isset($_POST['calificacion'])) {
    $usuarioId = $_POST['usuarioId'];
    $restauranteId = $_POST['restauranteId'];
    $descripcion = $_POST['descripcion'];
    $calificacion = $_POST['calificacion'];
    $fecha = date('Y-m-d'); // Fecha actual

    // Inserta la nueva reseña en la base de datos
    $sql = "INSERT INTO resenias (res_desc, res_cali, res_fecha, res_usu, res_rest) 
            VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Correcto: 's' para string (descripcion y fecha), 'i' para entero (usuarioId y restauranteId)
        $stmt->bind_param("sisii", $descripcion, $calificacion, $fecha, $usuarioId, $restauranteId);
        
        if ($stmt->execute()) {
            echo json_encode(array('mensaje' => 'Reseña añadida correctamente.'));
        } else {
            echo json_encode(array('mensaje' => 'Error al añadir la reseña.'));
        }

        $stmt->close();
    } else {
        echo json_encode(array('mensaje' => 'Error en la consulta.'));
    }
} else {
    echo json_encode(array('mensaje' => 'Faltan parámetros.'));
}

$conn->close();
?>
