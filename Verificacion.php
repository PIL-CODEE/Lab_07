<?php
session_start();

// Crear la conexión.
$conexion = new mysqli("localhost", "root", "", "db03");

// Verificar la conexión.
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Validación de datos.
$nombre = validarInput($_POST['nombre']);
$agente_id = validarInput($_POST['agente_id']);
$departamento_id = validarInput($_POST['departamento_id']);
$num_misiones = validarInput($_POST['num_misiones']);
$descripcion_mision = validarInput($_POST['descripcion_mision']);
$fecha_limite = validarInput($_POST['fecha_limite']);

// Cifrar el nombre y el ID del agente.
$nombre_cifrado = cifrarCampo($nombre);
$id_agente_cifrado = cifrarCampo($agente_id);
$fecha_cifrada = cifrarCampo($fecha_limite);

// Consulta SQL para insertar los datos en la tabla utilizando una consulta preparada.
$sql = "INSERT INTO agentes (nombre, agente_id, departamento_id, num_misiones, descripcion_mision, fecha_limite)
        VALUES (?, ?, ?, ?, ?, ?)";

// Preparar la consulta.
$stmt = $conexion->prepare($sql);

// Vincular parámetros y ejecutar la consulta.
$stmt->bind_param("ssiiis", $nombre_cifrado, $id_agente_cifrado, $departamento_id, $num_misiones, $descripcion_mision, $fecha_cifrada);

// Ejecutar la consulta.
if ($stmt->execute()) {
    // Redireccionar a otra pagina, donde podremos agregar más agentes.
    header("Location: Exito.php");
    exit();
} else {
    echo "Error al ejecutar la consulta: " . $stmt->error;
}

// Cerrar la conexión.
$stmt->close();
$conexion->close();

// Función para validar la entrada de datos y evitar ataques XSS.
function validarInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para cifrar un campo.
function cifrarCampo($data) {
    return md5($data);
}
?>

