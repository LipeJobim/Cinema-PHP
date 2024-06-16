<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['filme_id'])) {
    $filme_id = intval($_GET['filme_id']);
    $sql = "SELECT id, horario FROM horarios WHERE filme_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $filme_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $horarios[] = $row;
        }
        echo json_encode($horarios);
    }
}
?>