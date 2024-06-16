<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Tela de login</title>
</head>

<body>
    <a href="home.php">Voltar</a>
    <div>
        <h1>Login</h1>
        <form action="testelogin.php" method="POST">
            <input type="text" name="email" placeholder="Email">
            <br><br>
            <input type="password" name="senha" placeholder="Senha">
            <br><br>
            <input class="inputsubmit" type="submit" name="submit" value="Enviar">
        </form>
    </div>
</body>

</html>