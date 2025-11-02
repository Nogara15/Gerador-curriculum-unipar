<?php
require 'conexao.php';


$cpf = $_POST['cpf'];
$competencias = $_POST['competencia'];


$sucesso = true;

foreach ($competencias as $comp) {
    $comp = trim($comp);


    if (!empty($comp)) {
        $competencia = mysqli_real_escape_string($conexao, $comp);

        $sql = "INSERT INTO comp (cpf, competencia) VALUES ('$cpf', '$competencia')";

        if (!mysqli_query($conexao, $sql)) {
            $sucesso = false;
            echo 'Erro ao inserir registro: ' . mysqli_error($conexao);
            break;
        }
    }
}

if ($sucesso) {
    echo "<script>
            alert('Competências registradas com sucesso!');
            window.location.href = 'inicio.php?cpf=" . $cpf . "';
          </script>";
}

mysqli_close($conexao);
?>
