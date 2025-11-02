<?php
require 'conexao.php';

$cpf = $_POST['cpf'];
$cursos = $_POST['curso'];
$localidades = $_POST['localidade'];
$periodos = $_POST['periodo'];

$sucesso = true;


for ($i = 0; $i < count($cursos); $i++) {
    $curso = mysqli_real_escape_string($conexao, $cursos[$i]);
    $local = mysqli_real_escape_string($conexao, $localidades[$i]);
    $periodo = mysqli_real_escape_string($conexao, $periodos[$i]);


    if (!empty(trim($curso))) {
        $sql = "INSERT INTO formacao (cpf, curso, localidade, periodo) 
                VALUES ('$cpf', '$curso', '$local', '$periodo')";

        if (!mysqli_query($conexao, $sql)) {
            $sucesso = false;
            echo "Erro ao inserir registro: " . mysqli_error($conexao);
            break;
        }
    }
}

if ($sucesso) {
    echo "<script>
            alert('Formações inseridas com sucesso!');
            window.location.href = 'expir.html?cpf=" . $cpf . "';
          </script>";
}

mysqli_close($conexao);
?>
