<?php
$hostname_localhost = "localhost";
$database_localhost = "agenda";
$username_localhost = "angelq";
$pass_localhost = "123456";
$json = array();

if (isset($_GET["id"])) {
  $id = $_GET['id'];

  $conexion = new mysqli($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

  if ($conexion->connect_error) {
    $json['error'] = "Database connection failed: " . $conexion->connect_error;
    echo json_encode($json);
    exit();
  }

  $stmt = $conexion->prepare("DELETE FROM contacts WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
      $json['success'] = true;
      $json['message'] = "Contact deleted successfully";
    } else {
      $json['success'] = false;
      $json['message'] = "No contact found with the provided ID";
    }
  } else {
    $json['error'] = "Error executing query: " . $stmt->error;
  }

  $stmt->close();
  $conexion->close();
  echo json_encode($json);

} else {
  $json['error'] = "Missing id parameter";
  echo json_encode($json);
}
?>

