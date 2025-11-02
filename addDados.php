<?php
require 'conexao.php';

$numero = $_POST['numero'];
$email = $_POST['email'];
$estado = $_POST['estado'];
$cidade = $_POST['cidade'];
$sexo = $_POST['sexo'];
$dt_nascismento = $_POST['dt_nascismento'];
$perfil = $_POST['perfil'];
$cpf = $_POST['cpf'];

$img = null;
if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
    $pasta = "uploads/";
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    $nomeArquivo = basename($_FILES['img']['name']);
    $caminho = $pasta . uniqid() . "_" . $nomeArquivo;

    if (move_uploaded_file($_FILES['img']['tmp_name'], $caminho)) {
        $img = $caminho;
    }
}

$sql = "INSERT INTO dadospes (numero, email, estado, cidade, sexo, dt_nascismento, img, perfil, cpf) 
        VALUES ('$numero', '$email', '$estado', '$cidade', '$sexo', '$dt_nascismento', '$img', '$perfil', '$cpf')";

if (mysqli_query($conexao, $sql)) {
    echo "<script>
            alert('Registro inserido com sucesso!');
            window.location.href = 'form.html?cpf=" . $cpf . "';
          </script>";
} else {
    echo "Erro ao inserir registro: " . mysqli_error($conexao);
}

mysqli_close($conexao);
?>
