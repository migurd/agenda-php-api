<?php
$hostname_localhost = "localhost";
$database_localhost = "agenda";
$username_localhost = "angelq";
$pass_localhost = "123456";
$json = array();

if (isset($_GET['id_movil'])) {
	$idMovil = $_GET['id_movil'];

	$conexion = new mysqli($hostname_localhost, $username_localhost, $pass_localhost, $database_localhost);

	if ($conexion->connect_error) {
		$json['error'] = "Database connection failed: " . $conexion->connect_error;
		echo json_encode($json);
		exit();
	}

	$stmt = $conexion->prepare("SELECT * FROM contacts WHERE id_movil = ? ORDER BY name");
	$stmt->bind_param("s", $idMovil);

	if ($stmt->execute()) {
		$result = $stmt->get_result();

		while ($registro = $result->fetch_assoc()) {
			$json['contacts'][] = $registro;
		}

		$result->free();
	} else {
		$json['error'] = "Error executing query: " . $stmt->error;
	}

	$stmt->close();
	$conexion->close();
	echo json_encode($json);

} else {
	$json['error'] = "Missing id_movil parameter";
	echo json_encode($json);
}
?>
