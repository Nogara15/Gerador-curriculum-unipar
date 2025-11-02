<?php
require 'conexao.php';

$cpf = $_POST['cpf'];
$cargos = $_POST['cargo'];
$localidades = $_POST['localidade'];
$periodos = $_POST['periodo'];

$sucesso = true;


for ($i = 0; $i < count($cargos); $i++) {
    $cargo = mysqli_real_escape_string($conexao, $cargos[$i]);
    $local = mysqli_real_escape_string($conexao, $localidades[$i]);
    $periodo = mysqli_real_escape_string($conexao, $periodos[$i]);


    if (!empty(trim($cargo))) {
        $sql = "INSERT INTO expi (cpf, cargo, localidade, periodo) 
                VALUES ('$cpf', '$cargo', '$local', '$periodo')";

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
            window.location.href = 'compet.html?cpf=" . $cpf . "';
          </script>";
}

mysqli_close($conexao);
?>
