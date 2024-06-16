<?php
if (isset($_GET['id'])) { 
    $id_filme = intval($_GET['id']); 

    $conexao = new mysqli('localhost', 'root', 'root', 'cinema'); 
    if ($conexao->connect_error) {
        die("Erro ao conectar ao banco de dados: " . $conexao->connect_error);
    }

    $consulta = $conexao->prepare("SELECT * FROM filme WHERE id = ?");
    $consulta->bind_param('i', $id_filme);
    $consulta->execute();
    $resultado = $consulta->get_result();

    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="detalhes.css">
    <title>Detalhes do Filme</title>
</head>
<body>
<a href="catalogo.php">Voltar</a>
<?php
    if ($resultado->num_rows > 0) { 
        $filme = $resultado->fetch_assoc();
        
        echo '<div class="filme-detalhes">';
        echo "<h1>Detalhes do Filme</h1>";

        if (isset($filme['imagem']) && !empty($filme['imagem'])) {
            echo '<img src="imagens/' . $filme['imagem'] . '" alt="' . $filme['nome'] . '" style="max-width: 100%; height: auto;">';
        }

        echo "<p>Nome: " . $filme['nome'] . "</p>"; 
        echo "<p>Duração: " . $filme['duracao'] . " minutos</p>"; 
        echo "<p>Faixa Etária: " . $filme['faixaEtaria'] . "</p>"; 
        echo "<p>Valor: R$ " . number_format($filme['valor'], 2, ',', '.') . "</p>";

        if (isset($filme['youtube']) && !empty($filme['youtube'])) {
            echo '<div class="youtube-video">';
            echo '<iframe width="560" height="315" src="' . $filme['youtube'] . '" frameborder="0" allowfullscreen></iframe>';
            echo '</div>';
        }

        echo '</div>';
        
    } else { 
        echo '<div class="filme-detalhes error-message">';
        echo "Nenhum filme encontrado com o ID fornecido.";
        echo '</div>';
    }

    $consulta->close(); 
    $conexao->close(); 
} else {
    echo '<div class="filme-detalhes error-message">';
    echo "ID do filme não fornecido na URL.";
    echo '</div>';
}
?>

</body>
</html>
