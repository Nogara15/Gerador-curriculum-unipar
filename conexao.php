<?php
$conexao = mysqli_connect("localhost", "root", "", "curuni");


if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

?>