<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - Controle de alunos</title>
</head>

<body>
<a href="index.html">Home</a> | <a href="consulta.php">Consulta</a>
<hr>
<h2>Edição de Alunos</h2>
    
<?php    
    $ra = $_POST['ra'];
    $novoNome = $_POST['nome'];
    $novoCurso = $_POST['curso'];

    $uploaddir = 'upload/fotos/';
//foto

    $novaFoto = $_FILES['foto'];
    $nomeFoto = $novaFoto['name'];
    $tipoFoto = $novaFoto['type'];
    $tamanhoFoto =$novaFoto['size'];

    $info = new SplFileInfo($nomeFoto);
    $extensaoArq = $info->getExtension();
    $novoNomeFoto = $ra . "." . $extensaoArq;



        include("conexaoBD.php");

        if(($nomeFoto != "")&&(move_uploaded_file($_FILES['foto']['tmp_name'],$uploaddir . $novoNomeFoto))){
            $uploadfile = $uploaddir . $novoNomeFoto;

            $stmt = $pdo->prepare('UPDATE alunos SET nome = :novoNome, curso = :novoCurso, arquivoFoto = :novaFoto WHERE ra = :ra');
            $stmt->bindParam(':novoNome', $novoNome);
            $stmt->bindParam(':novoCurso', $novoCurso);
            $stmt->bindParam(':novaFoto',$uploadfile);
            $stmt->bindParam(':ra', $ra);
        }else{
            $stmt = $pdo->prepare('UPDATE alunos SET nome = :novoNome, curso = :novoCurso WHERE ra = :ra');
            $stmt->bindParam(':novoNome', $novoNome);
            $stmt->bindParam(':novoCurso', $novoCurso);
            $stmt->bindParam(':ra', $ra);
        }

    try {
        $stmt->execute();

        echo "Os dados do aluno de RA $ra foram alterados!";


    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    $pdo = null;
?>
</body>
</html>