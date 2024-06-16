<?php
include '../Cinema/config.php';

if(isset($_POST['submit']))
{
    $nome = $_POST['nome']; 
    $duracao = $_POST['duracao'];
    $faixaEtaria = $_POST['faixaEtaria'];
    $valor = $_POST['valor'];
    $imagem = $_POST['imagem'];

    $result = mysqli_query($conexao, "INSERT INTO filme (nome, duracao, faixaEtaria, valor, imagem) 
    VALUES ('$nome','$duracao','$faixaEtaria','$valor','$imagem')");
    
    if($result) {
        echo "Filme cadastrado com sucesso!";
        header('Location: home.php');
    } else {
        echo "Erro ao cadastrar o filme: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cadastro.css">

    <title>Cadastro</title>
</head>
<body>
    <div class="box">
        <form action="cadastroFilme.php" method="POST">
           <fieldset>
            <legend><b>Cadastro de filmes</b></legend>
            <br>
            <div class="inputbox">
                <input type="text" name="nome" id="nome" class="inputUser" required>
                <label for="nome" class="labelinput">Nome</label>
            </div>
            <br><br>
            <div class="inputbox">
                <input type="text" name="duracao" id="duracao" class="inputUser" required> 
                <label for="duracao"class="labelinput">Duração</label> 
            </div>
            <br><br>
            <div class="inputbox">
                <input type="text" name="faixaEtaria" id="faixaEtaria" class="inputUser" required>
                <label for="faixaEtaria"class="labelinput">Faixa Etária</label>
            </div>
            <br><br>
            <div class="inputbox">
                <input type="text" name="valor" id="valor" class="inputUser" required> 
                <label for="valor"class="labelinput">Valor</label> 
            <br><br>
            <div class="inputbox">
                <input type="text" name="imagem" id="imagem" class="inputUser" required>
                <label for="imagem"class="labelinput">Imagem</label>
            </div>
            <br><br>
            <input type="submit" name="submit" id="submit">
           </fieldset>
        </form>
    </div>
</body>
</html>
