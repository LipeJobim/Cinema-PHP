<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="file">Escolha uma imagem:</label>
    <input type="file" name="file" id="file" required>
    <input type="hidden" name="id_filme" value="1">
    <input type="submit" value="Fazer upload">
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_filme = intval($_POST['id_filme']);
    $target_dir = "imagens/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $upload_ok = true;

    
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check === false) {
        $upload_ok = false;
    }

    if (file_exists($target_file)) {
        $upload_ok = false;
    }

    
    if ($_FILES["file"]["size"] > 5000000) {
        $upload_ok = false;
    }

    if ($upload_ok) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $conexao = new mysqli('localhost', 'root', 'root', 'cinema');
            if ($conexao->connect_error) {
                die("Erro ao conectar ao banco de dados: " . $conexao->connect_error);
            }

            
            $consulta = $conexao->prepare("UPDATE filme SET imagem = ? WHERE id = ?");
            $consulta->bind_param('si',basename($target_file), $id_filme);
            $consulta->execute();
            $consulta->close();
            $conexao->close();

            echo "Upload realizado com sucesso!";
        } else {
            echo "Erro ao fazer upload da imagem.";
        }
    } else {
        echo "Arquivo não é válido para upload.";
    }
} else {
    echo "Método HTTP inválido.";
}
?>

