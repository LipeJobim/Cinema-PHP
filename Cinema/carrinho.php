<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filme_id = isset($_GET['filme']) ? $_GET['filme'] : '';
$dia = isset($_GET['dia']) ? $_GET['dia'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filme'], $_POST['horario'], $_POST['quantidade'], $_POST['dia'])) {
        $filme_id = $_POST['filme'];
        $horario = $_POST['horario'];
        $quantidade = $_POST['quantidade'];
        $dia = $_POST['dia'];

        
        $sql = "SELECT COUNT(*) AS count FROM filme_dias WHERE filme_id = ? AND dia = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("is", $filme_id, $dia);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                $erro = "Este filme não está disponível no dia selecionado.";
            } else {
              
                $sql = "SELECT valor FROM filme WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $filme_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $valor = $row['valor'];

             
                    $disponibilidade = 100;

                    $sql = "SELECT SUM(quantidade) AS total_vendidos FROM ingressos WHERE filme_id = ? AND horario = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("is", $filme_id, $horario);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();

                        $total_vendidos = $row['total_vendidos'] ?? 0;
                        $ingressos_disponiveis = $disponibilidade - $total_vendidos;

                        if ($quantidade <= $ingressos_disponiveis) {
                            $sql = "INSERT INTO ingressos (filme_id, horario, quantidade) VALUES (?, ?, ?)";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("isi", $filme_id, $horario, $quantidade);
                                if ($stmt->execute()) {
                                    $_SESSION['filme'] = $filme_id;
                                    $_SESSION['horario'] = $horario;
                                    $_SESSION['quantidade'] = $quantidade;
                                    $_SESSION['dia'] = $dia;
                                    $_SESSION['total'] = $quantidade * $valor;

                                    header('Location: confirmacao.php');
                                    exit;
                                } else {
                                    $erro = "Erro ao processar a compra: " . $stmt->error;
                                }
                            } else {
                                $erro = "Erro ao preparar a inserção: " . $conn->error;
                            }
                        } else {
                            $erro = "Quantidade de ingressos indisponível.";
                        }
                    } else {
                        $erro = "Erro ao preparar a consulta: " . $conn->error;
                    }
                } else {
                    $erro = "Erro ao buscar o valor do filme: " . $conn->error;
                }
            }
        } else {
            $erro = "Erro ao preparar a consulta: " . $conn->error;
        }
    } else {
        $erro = "Por favor, selecione o filme, o horário e a quantidade de ingressos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="carr.css">
    <title>Compra de Ingresso</title>
    <script>
        function loadHorarios(filmeId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_horarios.php?filme_id=' + filmeId, true);
            xhr.onload = function () {
                if (this.status == 200) {
                    var horarios = JSON.parse(this.responseText);
                    var select = document.getElementById('horario');
                    select.innerHTML = '';
                    horarios.forEach(function(horario) {
                        var option = document.createElement('option');
                        option.value = horario.horario;
                        option.textContent = horario.horario;
                        select.appendChild(option);
                    });
                }
            }
            xhr.send();
        }
    </script>
</head>
<body>
    <a href="catalogo.php">Voltar</a>
    <h1>Compra de Ingresso</h1>
    <?php if (isset($erro)) echo "<p>$erro</p>"; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="filme">Selecione o filme:</label>
        <select name="filme" id="filme" onchange="loadHorarios(this.value)">
            <option value="">Selecione um filme</option>
            <?php
            $sql = "SELECT id, nome FROM filme";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = ($row['id'] == $filme_id) ? 'selected' : '';
                    echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['nome'] . '</option>';
                }
            }
            ?>
        </select>
        <label for="horario">Selecione o horário:</label>
        <select name="horario" id="horario">
            <option value="">Selecione um horário</option>
            <?php
            if (!empty($filme_id)) {
                $sql = "SELECT horario FROM horarios WHERE filme_id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $filme_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['horario'] == $horario) ? 'selected' : '';
                        echo '<option value="' . $row['horario'] . '" ' . $selected . '>' . $row['horario'] . '</option>';
                    }
                }
            }
            ?>
        </select>
        <label for="dia">Selecione o dia:</label>
        <select name="dia" id="dia">
            <?php
            $dias_semana = array("Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado", "Domingo");
            foreach ($dias_semana as $dia_opcao) {
                $selected = ($dia_opcao == $dia) ? 'selected' : '';
                echo '<option value="' . $dia_opcao . '" ' . $selected . '>' . $dia_opcao . '</option>';
            }
            ?>
        </select>
        <label for="quantidade">Quantidade de Ingressos:</label>
        <input type="number" name="quantidade" id="quantidade" min="1" value="1">
        <input type="submit" value="Comprar">
    </form>
</body>
</html>
