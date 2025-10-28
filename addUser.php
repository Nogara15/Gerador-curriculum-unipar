<?php 

require 'conexao.php';

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];


$sql = "INSERT INTO users (nome, cpf) VALUES ('$nome', '$cpf')";


if (mysqli_query($conn, $sql)) {
    echo "Registro inserido com sucesso!";
} else {
    echo "Erro ao inserir registro: " . mysqli_error($conn);
}

mysqli_close($conn);

?>