<?php

include '../Cinema/config.php';

if(isset($_POST['submit']))
{
    include_once('config.php');
    
    $nome = $_POST['nome']; 
    $email = $_POST['email'];
    $senha = $_POST['senha'];


    $result = mysqli_query($conexao, "INSERT INTO usuario (nome, email, senha) 
    VALUES ('$nome','$email','$senha')");
    
    header('Location: login.php');
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
    <a href="home.php">Voltar</a>
    <div class="box">
        <form action="cadastro.php" method="POST">
           <fieldset>
            <legend><b>Cadastro de Cliente</b></legend>
            <br>
            <div class="inputbox">
                <input type="text" name="nome" id="nome" class="inputUser" required>
                <label for="nome" class="labelinput">Nome completo</label>
            </div>
            <br><br>
            <div class="inputbox">
                <input type="text" name="email" id="email" class="inputUser" required>
                <label for="email"class="labelinput">Email</label>
            </div>
            <br><br>
            <div class="inputbox">
                <input type="password" name="senha" id="senha" class="inputUser" required>
                <label for="senha"class="labelinput">Senha</label>
            </div>
            <br><br>
            <input type="submit" name="submit" id="submit">
           </fieldset>
        </form>
    </div>
</body>
</html>
