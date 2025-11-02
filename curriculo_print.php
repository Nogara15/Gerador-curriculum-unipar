<?php

if (!isset($cpf) || empty($cpf) || !isset($conexao)) {
    echo "<p>Dados insuficientes para imprimir.</p>";
    return;
}

function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

$sqlDados = "SELECT * FROM dadospes WHERE cpf = '" . mysqli_real_escape_string($conexao, $cpf) . "' LIMIT 1";
$resDados = mysqli_query($conexao, $sqlDados);
$dadosPrint = mysqli_fetch_assoc($resDados);

$sqlUser = "SELECT nome FROM users WHERE cpf = '" . mysqli_real_escape_string($conexao, $cpf) . "' LIMIT 1";
$resUser = mysqli_query($conexao, $sqlUser);
$userPrint = mysqli_fetch_assoc($resUser);

$sqlExp = "SELECT * FROM expi WHERE cpf = '" . mysqli_real_escape_string($conexao, $cpf) . "'";
$resExp = mysqli_query($conexao, $sqlExp);

$sqlForm = "SELECT * FROM formacao WHERE cpf = '" . mysqli_real_escape_string($conexao, $cpf) . "'";
$resForm = mysqli_query($conexao, $sqlForm);

$sqlComp = "SELECT * FROM comp WHERE cpf = '" . mysqli_real_escape_string($conexao, $cpf) . "'";
$resComp = mysqli_query($conexao, $sqlComp);

function calcularIdadePrint($dataNascimento) {
    if (empty($dataNascimento)) return '';
    $hoje = new DateTime();
    try {
        $nasc = new DateTime($dataNascimento);
    } catch (Exception $e) {
        return '';
    }
    return $hoje->diff($nasc)->y;
}
$idadePrint = calcularIdadePrint($dadosPrint['dt_nascismento'] ?? '');

$cidadeEstado = trim(($dadosPrint['cidade'] ?? '') . ($dadosPrint['estado'] ? ' - ' . $dadosPrint['estado'] : ''));

$imgSrc = !empty($dadosPrint['img']) ? $dadosPrint['img'] : 'imagens/semfoto.png';

?>


<style>
    @page { size: A4; margin: 20mm; }
    #print-wrapper {
        font-family: Arial, Helvetica, sans-serif;
        color: #111;
        width: 100%;
    }

    .print-header {
        display: flex;
        gap: 18px;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .print-photo {
        width: 120px;
        height: 160px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #f5f5f5;
        flex-shrink: 0;
    }

    .print-title h1 {
        margin: 0;
        font-size: 20px;
    }

    .print-personal {
        margin-top: 8px;
        font-size: 12px;
        line-height: 1.5;
    }

    .section {
        margin-top: 16px;
    }

    .section h2 {
        margin: 0 0 8px 0;
        font-size: 14px;
        background: #4b6cb7;
        color: #fff;
        padding: 6px 8px;
        border-radius: 4px;
    }

    .section .content {
        padding: 8px;
        border: 1px solid #eee;
        background: #fafafa;
        margin-top: 6px;
        font-size: 12px;
    }

    ul.simple-list {
        margin: 6px 0 0 18px;
        padding: 0;
    }
    ul.simple-list li {
        margin-bottom: 6px;
    }

    .no-break {
        page-break-inside: avoid;
    }

    .print-footer {
        margin-top: 18px;
        font-size: 10px;
        color: #666;
    }

    @media screen {
        .modal-print .modal-content { max-height: 80vh; overflow: auto; }
    }
</style>

<div id="print-wrapper">
    <div class="print-header no-break">
        <img class="print-photo" src="<?php echo h($imgSrc); ?>" alt="Foto">
        <div class="print-title">
            <h1>Curriculum de <?php echo h($userPrint['nome'] ?? ($dadosPrint['nome'] ?? '')); ?></h1>

            <div class="print-personal">
                <div><strong>Número:</strong> <?php echo h($dadosPrint['numero'] ?? ''); ?></div>
                <div><strong>E-mail:</strong> <?php echo h($dadosPrint['email'] ?? ''); ?></div>
                <div><strong>Cidade / Estado:</strong> <?php echo h($cidadeEstado); ?></div>
                <div><strong>Idade / Sexo:</strong> <?php echo h(($idadePrint !== '' ? $idadePrint . ' anos' : '') . ' - ' . ($dadosPrint['sexo'] ?? '')); ?></div>
            </div>
        </div>
    </div>

    <div class="section no-break">
        <h2>Perfil</h2>
        <div class="content">
            <p><?php echo nl2br(h($dadosPrint['perfil'] ?? '')); ?></p>
        </div>
    </div>

    <div class="section">
        <h2>Experiência</h2>
        <div class="content">
            <?php
            if ($resExp && mysqli_num_rows($resExp) > 0) {
                echo '<ul class="simple-list">';
                while ($row = mysqli_fetch_assoc($resExp)) {
                    $cargo = h($row['cargo'] ?? '');
                    $local = h($row['localidade'] ?? '');
                    $periodo = h($row['periodo'] ?? '');
                    $linha = trim("$cargo — $local ($periodo)");
                    echo '<li>' . ($linha ?: '&nbsp;') . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="content">Nenhuma experiência registrada.</div>';
            }
            ?>
        </div>
    </div>

    <div class="section">
        <h2>Formação</h2>
        <div class="content">
            <?php
            if ($resForm && mysqli_num_rows($resForm) > 0) {
                echo '<ul class="simple-list">';
                while ($row = mysqli_fetch_assoc($resForm)) {
                    $curso = h($row['curso'] ?? '');
                    $local = h($row['localidade'] ?? '');
                    $periodo = h($row['periodo'] ?? '');
                    $linha = trim("$curso — $local ($periodo)");
                    echo '<li>' . ($linha ?: '&nbsp;') . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="content">Nenhuma formação registrada.</div>';
            }
            ?>
        </div>
    </div>

    <div class="section">
        <h2>Competências</h2>
        <div class="content">
            <?php
            if ($resComp && mysqli_num_rows($resComp) > 0) {
                echo '<ul class="simple-list">';
                while ($row = mysqli_fetch_assoc($resComp)) {
                    $comp = h($row['competencia'] ?? '');
                    echo '<li>' . ($comp ?: '&nbsp;') . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="content">Nenhuma competência registrada.</div>';
            }
            ?>
        </div>
    </div>

    <div class="print-footer">
        Gerado por Gerador — <?= date('d/m/Y H:i') ?> — CPF: <?php echo h($cpf); ?>
    </div>
</div>
