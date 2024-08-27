<?php
$hostname_localhost = "localhost";
$database_localhost = "agenda";
$username_localhost = "angelq";
$pass_localhost = "123456";
$json = array();

if (isset($_GET["id"]) && isset($_GET["name"]) && isset($_GET["address"]) && isset($_GET["phone_number1"])) {
	$id = $_GET['id'];
	$name = $_GET['name'];
	$phone_number1 = $_GET['phone_number1'];
	$phone_number2 = $_GET['phone_number2'] ?? '';
	$address = $_GET['address'];
	$notes = $_GET['notes'] ?? '';
	$is_favorite = $_GET['is_favorite'];

	$conexion = new mysqli($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

	if ($conexion->connect_error) {
		$json['error'] = "Database connection failed: " . $conexion->connect_error;
		echo json_encode($json);
		exit();
	}

	$stmt = $conexion->prepare("UPDATE contacts SET name=?, phone_number1=?, phone_number2=?, address=?, notes=?, is_favorite=? WHERE id=?");
	$stmt->bind_param("sssssis", $name, $phone_number1, $phone_number2, $address, $notes, $is_favorite, $id);

	if ($stmt->execute()) {
		if ($stmt->affected_rows > 0) {
			$json['success'] = true;
			$json['message'] = 'Contact updated successfully';
		} else {
			$json['success'] = false;
			$json['message'] = 'No contact found with the provided ID';
		}
	} else {
		$json['success'] = false;
		$json['error'] = "Error updating contact: " . $stmt->error;
	}

	$stmt->close();
	$conexion->close();
	echo json_encode($json);

} else {
	$resulta["id"] = 0;
	$resulta["name"] = 'WS no retorna';
	$resulta["phone_number1"] = 'WS no retorna';
	$resulta["address"] = 'WS no retorna';
	$json['contacts'][] = $resulta;
	echo json_encode($json);
}
?>
