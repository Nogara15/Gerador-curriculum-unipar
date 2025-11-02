<?php
$cpf = $_GET['cpf'] ?? '';

if (empty($cpf)) {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador - Empresas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #7a7575ff;
            color: #fff;
        }

        .home-icon {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 24px;
            text-decoration: none;
            color: #000;
        }

        .titulo {
            margin-top: 50px;
            font-size: 24px;
            font-weight: bold;
            color: #b9a300;
            border: 1px solid #222;
            display: inline-block;
            padding: 6px 18px;
            border-radius: 6px;
        }

        .subtitulo {
            margin-top: 8px;
            font-size: 13px;
            color: #222;
            font-weight: bold;
        }

        .grid-empresas {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            justify-items: center;
            align-items: center;
            padding: 40px 120px;
        }

        .grid-empresas img {
            max-width: 220px;
            height: auto;
            transition: transform 0.2s ease-in-out;
            filter: grayscale(10%);
        }

        .grid-empresas img:hover {
            transform: scale(1.05);
            filter: grayscale(0%);
        }

        @media (max-width: 800px) {
            .grid-empresas {
                grid-template-columns: 1fr;
                padding: 30px;
                gap: 30px;
            }
        }
    </style>
</head>

<body>

    <a href="inicio.php?cpf=<?php echo urlencode($cpf); ?>" class="home-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
        </svg>
    </a>


    <h1 class="titulo">Gerador</h1>
    <p class="subtitulo">Empresas que têm acesso ao seu currículo</p>

    <div class="grid-empresas">
        <img src="logos/activision.png" alt="Activision">
        <img src="logos/bz.png" alt="Blizzard">
        <img src="logos/bandai.png" alt="Bandai Namco">
        <img src="logos/EA.png" alt="Electronic Arts">
        <img src="logos/rockstar.png" alt="Rockstar Games">
        <img src="https://upload.wikimedia.org/wikipedia/commons/3/31/Epic_Games_logo.svg" alt="Epic Games">
        <img src="logos/vv.png" alt="Valve">
        <img src="https://upload.wikimedia.org/wikipedia/commons/2/21/Nvidia_logo.svg" alt="NVIDIA">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Oracle_logo.svg" alt="Oracle">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">
        <img src="https://upload.wikimedia.org/wikipedia/commons/0/0d/Nintendo.svg" alt="Nintendo">
        <img src="logos/ps.png" alt="Sony">
    </div>

</body>

</html>