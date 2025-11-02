<?php
require 'conexao.php';

$cpf = $_GET['cpf'] ?? '';

if (empty($cpf)) {
    echo "<script>alert('CPF não informado.'); window.location.href='index.html';</script>";
    exit;
}

$sqlDados = "SELECT * FROM dadospes WHERE cpf = '$cpf' LIMIT 1";
$resultDados = mysqli_query($conexao, $sqlDados);
$dados = mysqli_fetch_assoc($resultDados);

$sqlUser = "SELECT nome FROM users WHERE cpf = '$cpf' LIMIT 1";
$resultUser = mysqli_query($conexao, $sqlUser);
$user = mysqli_fetch_assoc($resultUser);

function calcularIdade($dataNascimento)
{
    $hoje = new DateTime();
    $nascimento = new DateTime($dataNascimento);
    $idade = $hoje->diff($nascimento);
    return $idade->y;
}

$idade = isset($dados['dt_nascismento']) ? calcularIdade($dados['dt_nascismento']) : '';

$sqlExp = "SELECT * FROM expi WHERE cpf = '$cpf'";
$experiencias = mysqli_query($conexao, $sqlExp);

$sqlFormacao = "SELECT * FROM formacao WHERE cpf = '$cpf'";
$formacoes = mysqli_query($conexao, $sqlFormacao);

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
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Botão de menu */
        .menu-btn {
            position: absolute;
            top: 15px;
            left: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 26px;
            color: #333;
        }

        /* Overlay do menu */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: flex-start;
            align-items: stretch;
            z-index: 1000;
        }

        /* Conteúdo do menu lateral */
        .menu {
            background: #fff;
            width: 250px;
            height: 100%;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .menu a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .menu a:hover {
            background: #f5d07d;
        }

        /* Modal de impressão */
        .modal-print {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 70%;
            max-height: 90%;
            overflow-y: auto;
        }

        .close-modal {
            float: right;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .modal-print,
            .modal-print * {
                visibility: visible;
            }

            .modal-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: auto;
            }
        }

        /* Resto do layout */
        .cabecalho {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 30px;
            padding: 20px 80px;
        }

        .cabecalho img {
            width: 180px;
            height: 230px;
            object-fit: cover;
            object-position: center top;
            border-radius: 6px;
            border: 2px solid #ccc;
            background-color: #f5f5f5;
            flex-shrink: 0;
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

    <button class="menu-btn" id="menuBtn">☰</button>

    <div class="overlay" id="overlay">
        <div class="menu">
            <a href="#" id="btnPrint">🖨️ Impressão</a>
            <a href="empresas.php?cpf=<?php echo urlencode($cpf); ?>">🏢 Empresas</a>
            <a href="index.html">🚪 Sair</a>
        </div>
    </div>

    <div class="modal-print" id="modalPrint">
        <div class="modal-content" id="printContent">
            <span class="close-modal" id="closePrint">&times;</span>
            <h2>Currículo Completo</h2>
            <div id="curriculoCompleto">
                <?php include 'curriculo_print.php'; ?>
            </div>
            <button onclick="window.print()" style="margin-top:20px;">Imprimir Página</button>
        </div>
    </div>

    <h1 class="titulo">Gerador</h1>

    <div class="cabecalho">
        <img src="<?php echo !empty($dados['img']) ? $dados['img'] : 'imagens/semfoto.png'; ?>" alt="Foto do usuário">
        <div class="info">
            <h2>Curriculum de <?php echo htmlspecialchars($user['nome'] ?? ''); ?></h2>
            <p>Número: <?php echo htmlspecialchars($dados['numero'] ?? ''); ?></p>
            <p>E-mail: <?php echo htmlspecialchars($dados['email'] ?? ''); ?></p>
            <p>Cidade / Estado:
                <?php echo htmlspecialchars(($dados['cidade'] ?? '') . ' - ' . ($dados['estado'] ?? '')); ?>
            </p>
            <p>Idade / Sexo: <?php echo htmlspecialchars($idade . ' anos - ' . ($dados['sexo'] ?? '')); ?></p>
        </div>
    </div>

    <div class="perfil">
        <h3>Perfil</h3>
        <p><?php echo nl2br(htmlspecialchars($dados['perfil'] ?? '')); ?></p>
    </div>

    <div class="botoes">
        <button class="botao-acao" onclick="mostrarSecao('exp')">Experiência</button>
        <button class="botao-acao" onclick="mostrarSecao('formacao')">Formação</button>
        <button class="botao-acao" onclick="mostrarSecao('comp')">Competências</button>
    </div>

    <div class="conteudo" id="conteudo">
        <p style="text-align:center; color:#888;">Selecione uma seção acima para visualizar os detalhes.</p>
    </div>

    <script>
        const conteudo = document.getElementById('conteudo');
        const overlay = document.getElementById('overlay');
        const menuBtn = document.getElementById('menuBtn');
        const modalPrint = document.getElementById('modalPrint');
        const closePrint = document.getElementById('closePrint');
        const btnPrint = document.getElementById('btnPrint');

        menuBtn.addEventListener('click', () => overlay.style.display = 'flex');
        overlay.addEventListener('click', e => {
            if (e.target === overlay) overlay.style.display = 'none';
        });

        btnPrint.addEventListener('click', () => {
            modalPrint.style.display = 'flex';
            overlay.style.display = 'none';
        });

        closePrint.addEventListener('click', () => modalPrint.style.display = 'none');

        <?php
        echo "const experiencias = [";
        while ($exp = mysqli_fetch_assoc($experiencias)) {
            $cargo = htmlspecialchars($exp['cargo'] ?? '');
            $local = htmlspecialchars($exp['localidade'] ?? '');
            $periodo = htmlspecialchars($exp['periodo'] ?? '');
            $texto = trim("$cargo — $local ($periodo)");
            echo "'$texto',";
        }
        echo "];";

        echo "const formacoes = [";
        while ($form = mysqli_fetch_assoc($formacoes)) {
            $curso = htmlspecialchars($form['curso'] ?? '');
            $local = htmlspecialchars($form['localidade'] ?? '');
            $periodo = htmlspecialchars($form['periodo'] ?? '');
            $texto = trim("$curso — $local ($periodo)");
            echo "'$texto',";
        }
        echo "];";

        echo "const competencias = [";
        while ($comp = mysqli_fetch_assoc($competencias)) {
            $texto = htmlspecialchars($comp['competencia'] ?? '');
            echo "'$texto',";
        }
        echo "];";
        ?>

        function mostrarSecao(secao) {
            let html = '';
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
