<?php
require 'conexao.php';

// Pega o CPF vindo da URL
$cpf = $_GET['cpf'] ?? '';

if (empty($cpf)) {
    echo "<script>alert('CPF não informado.'); window.location.href='index.html';</script>";
    exit;
}

// Busca dados principais
$sqlDados = "SELECT * FROM dadospes WHERE cpf = '$cpf' LIMIT 1";
$resultDados = mysqli_query($conexao, $sqlDados);
$dados = mysqli_fetch_assoc($resultDados);

// Busca nome do usuário
$sqlUser = "SELECT nome FROM users WHERE cpf = '$cpf' LIMIT 1";
$resultUser = mysqli_query($conexao, $sqlUser);
$user = mysqli_fetch_assoc($resultUser);

// Função para calcular idade
function calcularIdade($dataNascimento) {
    $hoje = new DateTime();
    $nascimento = new DateTime($dataNascimento);
    $idade = $hoje->diff($nascimento);
    return $idade->y;
}

$idade = isset($dados['dt_nascismento']) ? calcularIdade($dados['dt_nascismento']) : '';

// Busca experiência
$sqlExp = "SELECT * FROM expi WHERE cpf = '$cpf'";
$experiencias = mysqli_query($conexao, $sqlExp);

// Busca formação
$sqlFormacao = "SELECT * FROM formacao WHERE cpf = '$cpf'";
$formacoes = mysqli_query($conexao, $sqlFormacao);

// Busca competências
$sqlComp = "SELECT * FROM comp WHERE cpf = '$cpf'";
$competencias = mysqli_query($conexao, $sqlComp);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador - Currículo</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* pequenas garantias visuais */
        .cabecalho {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px 60px;
        }
        .cabecalho img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .perfil {
            margin: 20px 60px;
            background-color: #f6f6f6;
            border-radius: 8px;
            padding: 15px;
        }
        .perfil h3 {
            background-color: #4b6cb7;
            color: white;
            text-align: center;
            margin: 0;
            padding: 8px;
            border-radius: 4px 4px 0 0;
        }
        .perfil p {
            padding: 10px;
            text-align: justify;
        }
        .botoes {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 30px;
        }
        .botao-acao {
            background-color: #f5d07d;
            border: none;
            padding: 12px 28px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }
        .conteudo {
            margin: 30px 60px;
        }
    </style>
</head>
<body>

    <h1 class="titulo">Gerador</h1>

    <!-- Cabeçalho -->
    <div class="cabecalho">
        <img src="<?php echo !empty($dados['img']) ? $dados['img'] : 'imagens/semfoto.png'; ?>" alt="Foto do usuário">
        <div class="info">
            <h2>Curriculum de <?php echo htmlspecialchars($user['nome'] ?? ''); ?></h2>
            <p>Número: <?php echo htmlspecialchars($dados['numero'] ?? ''); ?></p>
            <p>E-mail: <?php echo htmlspecialchars($dados['email'] ?? ''); ?></p>
            <p>Cidade / Estado: <?php echo htmlspecialchars(($dados['cidade'] ?? '') . ' - ' . ($dados['estado'] ?? '')); ?></p>
            <p>Idade / Sexo: <?php echo htmlspecialchars($idade . ' anos - ' . ($dados['sexo'] ?? '')); ?></p>
        </div>
    </div>

    <!-- Perfil -->
    <div class="perfil">
        <h3>Perfil</h3>
        <p><?php echo nl2br(htmlspecialchars($dados['perfil'] ?? '')); ?></p>
    </div>

    <!-- Botões -->
    <div class="botoes">
        <button class="botao-acao" onclick="mostrarSecao('exp')">Experiência</button>
        <button class="botao-acao" onclick="mostrarSecao('formacao')">Formação</button>
        <button class="botao-acao" onclick="mostrarSecao('comp')">Competências</button>
    </div>

    <!-- Conteúdo dinâmico -->
    <div class="conteudo" id="conteudo">
        <p style="text-align:center; color:#888;">Selecione uma seção acima para visualizar os detalhes.</p>
    </div>

    <script>
        const conteudo = document.getElementById('conteudo');

        function mostrarSecao(secao) {
            let html = '';

            <?php
            // Experiência
            echo "const experiencias = [";
            while ($exp = mysqli_fetch_assoc($experiencias)) {
                $texto = htmlspecialchars($exp['descricao'] ?? '');
                echo "'$texto',";
            }
            echo "];";

            // Formação
            echo "const formacoes = [";
            while ($form = mysqli_fetch_assoc($formacoes)) {
                $texto = htmlspecialchars($form['competencia'] ?? $form['curso'] ?? '');
                echo "'$texto',";
            }
            echo "];";

            // Competências
            echo "const competencias = [";
            while ($comp = mysqli_fetch_assoc($competencias)) {
                $texto = htmlspecialchars($comp['competencia'] ?? '');
                echo "'$texto',";
            }
            echo "];";
            ?>

            if (secao === 'exp') {
                html = '<h3>Experiência</h3><ul>' + experiencias.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else if (secao === 'formacao') {
                html = '<h3>Formação</h3><ul>' + formacoes.map(f => `<li>${f}</li>`).join('') + '</ul>';
            } else if (secao === 'comp') {
                html = '<h3>Competências</h3><ul>' + competencias.map(c => `<li>${c}</li>`).join('') + '</ul>';
            }

            conteudo.innerHTML = html || '<p>Nenhum registro encontrado.</p>';
        }
    </script>

</body>
</html>
