<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Cadastro de Animais</title>
    <!-- Adicione a biblioteca Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        h1 {
            margin: 0;
        }

        .menu {
            text-align: center;
            margin-top: 20px;
        }

        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .menu a:hover {
            background-color: #0056b3;
        }

        /* Estilos para o carrossel */
        .carousel {
            margin-top: 20px;
        }
                /* Estilos para as imagens no carrossel */
                .carousel-inner img {
            max-width: 100%;
            max-height: 300px; /* Defina a altura máxima desejada */
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Menu</h1>
    </header>
    <!-- Adicione o carrossel Bootstrap -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                
                <img src="upload/fotos/Catarina.png" alt="Imagem 1">
            </div>
           
            <?php 
            include("bd.php");
            carrossel();
            ?>
        </div>
    </div>
    <div class="menu">
        <a href="cadastra.php">Cadastrar</a>
        <a href="consulta.php">Consultar</a>
    </div>

    <!-- Adicione a biblioteca Bootstrap JS e jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
