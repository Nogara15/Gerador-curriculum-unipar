<?php
require 'conexao.php';
header('Content-Type: application/json; charset=utf-8');

// Lê JSON do corpo
$input = json_decode(file_get_contents('php://input'), true);
$cpf = $input['cpf'] ?? '';

if (!$cpf) {
    echo json_encode(['success' => false, 'error' => 'CPF vazio']);
    exit;
}

// Normaliza CPF removendo tudo que não seja dígito (opcional)
$cpfClean = preg_replace('/\D+/', '', $cpf);

// Conexão (ajuste para seu ambiente)
$host = 'localhost';
$db   = 'seubanco';
$user = 'seuusuario';
$pass = 'suasenha';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Consulta usando prepared statement
    $stmt = $pdo->prepare('SELECT nome FROM users WHERE REPLACE(REPLACE(REPLACE(cpf, ".", ""), "-", ""), " ", "") = :cpf');
    $stmt->execute([':cpf' => $cpfClean]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo json_encode(['success' => true, 'nome' => $row['nome']]);
    } else {
        echo json_encode(['success' => false]);
    }

} catch (PDOException $e) {
    // Em produção não exponha erros; aqui é só para depuração
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro no banco']);
}
