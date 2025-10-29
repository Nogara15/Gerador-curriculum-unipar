<?php
include 'conexao.php';

header('Content-Type: application/json');

$cpf = $_POST['cpf'] ?? '';

if (empty($cpf)) {
    echo json_encode(['success' => false, 'message' => 'CPF não enviado']);
    exit;
}

$query = "SELECT * FROM users WHERE cpf = ?";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, "s", $cpf);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
}
?>
