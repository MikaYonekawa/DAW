<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consulta de Animais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="style.css">

</head>

<body>
    <header>
        <a href="index.php"><i class="fas fa-arrow-left"></i></a> <!-- Ícone de voltar -->
        <h1>Consulta de Animais</h1>
        <div></div> <!-- Espaço vazio para alinhar o ícone à direita -->
    </header>
    <div class="container">
        <div class="search-container">
            <form method="post">
                <input type="text" id="search" placeholder="Pesquisar código..." name="id">
                <input class="search-btn" type="submit" value="Consultar">

            </form>
        </div>
</body>

</html>
<?php
include("bd.php");
buscar();
?>