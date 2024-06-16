<?php

    $dbhost = 'Localhost';
    $dbUsername = 'root';
    $dbPassword = 'root';
    $dbName = 'cinema';

    $conexao = new mysqli( $dbhost,$dbUsername,$dbPassword,$dbName);

   //if($conexao->connect_errno)
    //{
    //  echo "erro";
    //}
    //else
    //{
    //   echo "conexão efetuada com sucesso";
    //} 

?>
