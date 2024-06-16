<?php
session_start();

if (!isset($_SESSION['filme'], $_SESSION['horario'], $_SESSION['quantidade'], $_SESSION['total'], $_SESSION['dia'])) {
    header('Location: index.php');
    exit;
}

$filme_id = $_SESSION['filme'];
$horario = $_SESSION['horario'];
$quantidade = $_SESSION['quantidade'];
$total = $_SESSION['total'];
$dia = $_SESSION['dia']; 

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT nome FROM filme WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $filme_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $filme_nome = $row['nome'];
} else {
    $filme_nome = "Desconhecido";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="confir.css">
    <title>Recibo de Compra</title>
</head>
<body>
    <div class="container">
        <a href="catalogo.php" class="top-left">Voltar</a>
        <h1>Recibo de Compra</h1>
        <p>Filme: <?php echo htmlspecialchars($filme_nome); ?></p>
        <p>Horário: <?php echo htmlspecialchars($horario); ?></p>
        <p>Quantidade de Ingressos: <?php echo htmlspecialchars($quantidade); ?></p>
        <p>Dia: <?php echo htmlspecialchars($dia); ?></p>
        <p>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
        <a href="#" class="botao-imprimir" onclick="imprimirRecibo()">Imprimir</a>
    </div>

    <script>
        function imprimirRecibo() {
            window.print();
        }
    </script>
</body>
</html>
