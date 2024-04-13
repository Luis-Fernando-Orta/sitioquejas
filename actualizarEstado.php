<?php
// Incluir la conexión a la base de datos
require('conexion.php');

// Verificar si los datos fueron enviados mediante una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos enviados desde la solicitud AJAX
    $id_queja = isset($_POST['id_queja']) ? intval($_POST['id_queja']) : null;
    $nuevo_estado = isset($_POST['nuevo_estado']) ? $_POST['nuevo_estado'] : null;

    // Verificar si los datos son válidos
    if ($id_queja !== null && !empty($nuevo_estado)) {
        // Preparar la consulta SQL para actualizar el estado de la queja
        $consulta = "UPDATE quejas SET estado = ? WHERE id_queja = ?";
        $stmt = mysqli_prepare($conectado, $consulta);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros a la consulta
            mysqli_stmt_bind_param($stmt, 'si', $nuevo_estado, $id_queja);

            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                // Consulta ejecutada con éxito, devolver una respuesta JSON indicando éxito
                $response = ['success' => true];
            } else {
                // Error al ejecutar la consulta
                $response = ['success' => false, 'error' => 'Error al ejecutar la consulta'];
            }

            // Cerrar la declaración preparada
            mysqli_stmt_close($stmt);
        } else {
            // Error al preparar la consulta
            $response = ['success' => false, 'error' => 'Error al preparar la consulta'];
        }
    } else {
        // Datos de entrada inválidos
        $response = ['success' => false, 'error' => 'Datos de entrada inválidos'];
    }

    // Devolver la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Solicitud incorrecta
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}

// Cerrar la conexión a la base de datos
mysqli_close($conectado);
?>
