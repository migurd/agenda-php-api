<?php
$hostname_localhost = "localhost";
$database_localhost = "agenda";
$username_localhost = "angelq";
$pass_localhost = "123456";
$json = array();

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Capture all output and discard it to prevent it from being sent to the client
ob_start();

// Function to handle errors and return JSON
function respondWithError($message) {
    header('Content-Type: application/json');
    $json = array("success" => false, "error" => $message);
    ob_end_clean();
    echo json_encode($json);
    exit();
}

if (isset($_GET["name"]) && isset($_GET["phone_number1"]) && isset($_GET["address"])) {
    $name = $_GET['name'];
    $phone_number1 = $_GET['phone_number1'];
    $phone_number2 = $_GET['phone_number2'] ?? '';
    $address = $_GET['address'];
    $notes = $_GET['notes'] ?? '';
    $is_favorite = $_GET['is_favorite'];
    $id_movil = $_GET['id_movil'];

    $conexion = new mysqli($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

    if ($conexion->connect_error) {
        respondWithError("Database connection failed: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("INSERT INTO contacts (name, phone_number1, phone_number2, address, notes, is_favorite, id_movil) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        respondWithError("Prepare failed: (" . $conexion->errno . ") " . $conexion->error);
    }

    $bind = $stmt->bind_param("sssssis", $name, $phone_number1, $phone_number2, $address, $notes, $is_favorite, $id_movil);
    if ($bind === false) {
        respondWithError("Bind failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $exec = $stmt->execute();
    if ($exec) {
        $alumnos = array();
        $query = $conexion->query("SELECT * FROM contacts WHERE id_movil = '$id_movil'");
        
        while($fila = $query->fetch_assoc()) {
            $alumnos[] = array(
                "id" => $fila["id"],
                "name" => $fila["name"],
                "phone_number1" => $fila["phone_number1"],
                "phone_number2" => $fila["phone_number2"],
                "address" => $fila["address"],
                "notes" => $fila["notes"],
                "is_favorite" => $fila["is_favorite"],
                "id_movil" => $fila["id_movil"]
            );
        }

        $json = json_encode($alumnos);
        header('Content-Type: application/json');
        ob_end_clean();
        echo $json;
    } else {
        respondWithError("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $stmt->close();
    $conexion->close();

} else {
    respondWithError("Missing required parameters");
}
?>

