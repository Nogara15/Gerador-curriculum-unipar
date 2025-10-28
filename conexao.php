<?php 
$conn = mysqli_connect("localhost", "root", "", "curuni");

// Verificar se conectou com sucesso
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
echo "Conexão bem-sucedida!";
?>