<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dias_semana = array(
    "Segunda-feira", "Terça-feira", "Quarta-feira", 
    "Quinta-feira", "Sexta-feira", "Sábado", "Domingo"
);

$filmes_por_dia = array();

foreach ($dias_semana as $dia) {
    $sql = "SELECT filme.id, filme.nome, filme.imagem, GROUP_CONCAT(horarios.horario SEPARATOR '|') AS horarios
            FROM filme
            INNER JOIN filme_dias ON filme.id = filme_dias.filme_id
            INNER JOIN horarios ON filme.id = horarios.filme_id
            WHERE filme_dias.dia = ?
            GROUP BY filme.id, filme.nome, filme.imagem";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $dia);
        $stmt->execute();
        $result = $stmt->get_result();

        $filmes_por_dia[$dia] = array();

        while ($row = $result->fetch_assoc()) {
            $filme = array(
                "id" => $row["id"],
                "nome" => $row["nome"],
                "imagem" => $row["imagem"],
                "horarios" => explode('|', $row["horarios"])
            );

            $filmes_por_dia[$dia][] = $filme;
        }

        $stmt->close();
    } else {
        die("Erro na preparação da consulta: " . $conn->error);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cat.css">
    <title>Catálogo de Filmes</title>
</head>
<body>
    <div class="d-flex">
        <a href="sair.php" class="">Encerrar</a>
        <div class="navbar">
            <h1 class="h1">FILMES EM CARTAZ</h1>
        </div>
        <?php
        foreach ($filmes_por_dia as $dia => $filmes) {
            echo '<h2>' . $dia . '</h2>';
            echo '<div class="catalogo">';

            foreach ($filmes as $filme) {
                echo '<div class="produto">';
                if (!empty($filme["imagem"])) {
                    echo '<img src="imagens/' . $filme["imagem"] . '" alt="' . $filme["nome"] . '">';
                }
                echo '<h3>' . $filme["nome"] . '</h3>';

                echo '<p>Horários: ' . implode(' | ', $filme["horarios"]) . '</p>';
                echo '<a href="detalhes.php?id=' . $filme["id"] . '">Detalhes</a>';
                echo '<a href="carrinho.php?filme=' . $filme["id"] . '&dia=' . urlencode($dia) . '">Comprar Ingresso</a>';
                echo '</div>';
            }

            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
