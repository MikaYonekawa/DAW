<?php
function conexaoBD()
{
    try {
        
        $db = 'mysql:host=143.106.241.3;dbname=cl201272;charset=utf8';
        $user = 'cl201272';
        $passwd = 'cl*26082005';
        $pdo = new PDO($db, $user, $passwd);

       
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        $output = 'Impossível conectar BD : ' . $e . '<br>';
        echo $output;
    }
    return $pdo;
}
function cadastrar()
{
    define('TAMANHO_MAXIMO', (2 * 1024 * 1024));
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        //inserindo dados
        $id = $_POST['id'];
        $nomePet = $_POST["nomePet"];
        $nomeDono = $_POST['nomeDono'];
        $raca = $_POST['raca'];
        $idade = $_POST['idade'];
        $foto = $_FILES['foto'];

        try {
            //upload dir
            $uploaddir = 'upload/fotos/'; //diretorio ondde será gravado a foto

            //foto
            $foto = $_FILES['foto'];
            $nomeFoto = $foto['name'];
            $tipoFoto = $foto['type'];
            $tamanhoFoto = $foto['size'];

            //gerando novo nome para a foto
            $info = new SplFileInfo($nomeFoto);
            $extensaoArq = $info->getExtension();
            $novoNomeFoto = $nomePet . "." . $extensaoArq;

            if ((trim($nomePet) == "") || (trim($id) == "") || (trim($nomeDono) == "") || (trim($raca) == "") ||
            (trim($idade)=="")) {
                echo "<div class='container' style='background-color: red; color: white; text-align: center; padding: 10px; border-radius: 5px;'>
                        Todos os campos são obrigatórios!";
                echo "</div>";

            } else if (($nomeFoto != "") && (!preg_match('/^image\/(jpg|jpeg|png|gif)$/', $tipoFoto))) {
                echo "<div class='container' style='background-color: red; color: white; text-align: center; padding: 10px; border-radius: 5px;'>
                        Não é uma imagem válida!";
                echo "</div>";
            } //validacaso tamanho do arquivo
            else if ($tamanhoFoto > TAMANHO_MAXIMO) {
                echo "<div class='container' style='background-color: red; color: white; text-align: center; padding: 10px; border-radius: 5px;'>
                        A imagem deve possuir no máximo 2MB!";
                echo "</div>";
            } else {
                $pdo = conexaoBD();
                $rows = buscarCadastro($id, $pdo);

                if ($rows <= 0) {
                    if (($nomeFoto != "") && (move_uploaded_file($_FILES['foto']['tmp_name'], $uploaddir . $novoNomeFoto))) {
                        $uploadfile = $uploaddir . $novoNomeFoto;

                    } else {
                        $uploadfile = null;
                        echo "<div class='container' style='background-color: yellow; color: white; text-align: center; padding: 10px; border-radius: 5px;'>
                                Sem upload de foto";
                        echo "</div>";

                    }

                    $stmt = $pdo->prepare("insert into pet (id, nomePet, nomeDono, raca, idade, foto) values(:id, :nomePet, :nomeDono, :raca, :idade, :foto)");
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':nomePet', $nomePet);
                    $stmt->bindParam(':nomeDono', $nomeDono);
                    $stmt->bindParam(':raca', $raca);
                    $stmt->bindParam(':idade', $idade);
                    $stmt->bindParam(':foto', $uploadfile);
                    $stmt->execute();

                    echo "<div class='container' style='background-color: #4CAF50; color: white; text-align: center; padding: 10px; border-radius: 5px;'>";
                    echo "Pet cadastrado com sucesso";
                    echo "</div>";
                } else {
                    echo "<div class='container' style='background-color: red; color: white; text-align: center; padding: 10px; border-radius: 5px;'>";
                    echo "Pet já existente";
                    echo "</div>";
                }
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    $pdo = conexaoBD();
}
function buscarCadastro($id, $pdo)
{
    $stmt = $pdo->prepare("select * from pet where id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $rows = $stmt->rowCount();
    return $rows;
}
function consultar()
{
    $pdo = conexaoBD();
    if (isset($_POST["id"]) && ($_POST["id"] != "")) {
        $id = $_POST["id"];
        $stmt = $pdo->prepare("select * from pet where id= :id order by nomePet");
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare("select * from pet order by nomePet");
    }
    $stmt->execute();
    return $stmt;
}
function carrossel()
{
    $stmt = consultar();
    while ($row = $stmt->fetch()) {
        echo '
        <div class="carousel-item">
            <img src=" ' . $row["foto"] . ' ">
        </div>';
    }
}
function buscar()
{
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        try {
              if (isset($_POST['id']) && isset($_POST['op'])) {
                $id = $_POST['id'];
                $op = $_POST['op'];
                switch ($op) {
                    case 'Excluir':
                        excluir($id);
                        break;
                    case 'Editar':
                        include('edicao.php'); // Implemente a lógica de edição
                        break;
                }
            }
            if(!isset($_POST['op'])){
            $stmt = consultar();
            echo '<form>
                    <label>
                        <input type="radio" name="ordenacao" value="alfabetica" onchange="ordenarTabela(\'alfabetica\')"> Ordenar Alfabeticamente
                        </label>';
                echo '<label>';
                echo '<input type="radio" name="ordenacao" value="numerica" onchange="ordenarTabela(\'numerica\')"> Ordenar Numericamente';
                echo '</label>';
            echo '</form>';

            echo "<form method='POST'><table id='tabela'>";
            echo "<thead>
                     <tr>
                        <th>Código</th>
                         <th>Nome do Pet</th>
                         <th>Nome do Dono</th>
                         <th>Raça do Pet</th>
                         <th>Idade do Pet</th>
                         <th>Foto Pet</th>
                         <th>Ações</th>
                     </tr>
                 </thead>";

            while ($row = $stmt->fetch()) {
                echo "<tr>";

                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nomePet'] . "</td>";
                echo "<td>" . $row['nomeDono'] . "</td>";
                echo "<td>" . $row['raca'] . "</td>";
                echo "<td>" . $row['idade'] . "</td>";

                if ($row["foto"] == null) {
                    echo "<td align='center'><img src='upload/patinha.png' width='50px' height='50px'></td>";

                } else {
                    echo "<td align='center'><img src=" . $row['foto'] . " width='50px' height='50px'></td>";

                }
                echo "
                        <td>
                            <form method='POST'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='btn btn-edit' formaction='edicao.php'>Editar</button>
                                <input type='submit' class='btn btn-delete' value='Excluir' name='op'>
                            </form>
                        </td>
                    </tr>
                    ";
            }

            echo "</table>";
            echo '</form>';
        }
          
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

    }
}
?>
<script>
    function ordenarTabela(ordenacao) {
        // Use JavaScript para ordenar a tabela em tempo real
        const tabela = document.getElementById("tabela");
        const tbody = tabela.querySelector("tbody");
        const linhas = Array.from(tbody.querySelectorAll("tr"));

        // Ordenar as linhas com base na escolha do usuário
        if (ordenacao === "alfabetica") {
            linhas.sort((a, b) => {
                const nomeA = a.querySelector("td:nth-child(2)").textContent;
                const nomeB = b.querySelector("td:nth-child(2)").textContent;
                return nomeA.localeCompare(nomeB);
            });
        } else if (ordenacao === "numerica") {
            linhas.sort((a, b) => {
                const codA = parseInt(a.querySelector("td:nth-child(1)").textContent, 10);
                const codB = parseInt(b.querySelector("td:nth-child(1)").textContent, 10);
                return codA - codB;
            });
        }

        // Limpar o tbody
        tbody.innerHTML = "";

        // Adicionar as linhas ordenadas de volta à tabela
        linhas.forEach((linha) => {
            tbody.appendChild(linha);
        });
    }

    // Chame a função para carregar a tabela inicialmente
    ordenarTabela("alfabetica");
</script>
<?php
function excluir($id)
{
    try {
        $pdo = conexaoBD();
        $stmt = $pdo->prepare('DELETE FROM pet WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt = buscarCadastro($id,$pdo);
        


        echo "<br><div style='background-color: red; color: white; text-align: center; padding: 10px; border-radius: 5px;'>";
        echo "Os dados do pet de código $id foram excluídos!";
        echo "</div>";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
function buscarEdicao($id)
{
    $pdo = conexaoBD();
    $stmt = $pdo->prepare('select * from pet where id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
}

function editar($id, $nomePet, $nomeDono, $raca, $idade, $foto)
{
    $pdo = conexaoBD();
    $uploaddir = 'upload/fotos/'; //diretorio ondde será gravado a foto

    //foto
    $foto = $_FILES['foto'];
    $nomeFoto = $foto['name'];
    $tipoFoto = $foto['type'];
    $tamanhoFoto = $foto['size'];

    //gerando novo nome para a foto
    $info = new SplFileInfo($nomeFoto);
    $extensaoArq = $info->getExtension();
    $novoNomeFoto = $nomePet . "." . $extensaoArq;

    if (($nomeFoto != "") && (move_uploaded_file($_FILES['foto']['tmp_name'], $uploaddir . $novoNomeFoto))) {
        $uploadfile = $uploaddir . $novoNomeFoto;

        $stmt = $pdo->prepare('UPDATE pet SET nomePet = :nomePet, nomeDono = :nomeDono, raca = :raca, idade = :idade, foto = :foto WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nomePet', $nomePet);
        $stmt->bindParam(':nomeDono', $nomeDono);
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':idade', $idade);
        $stmt->bindParam(':foto', $uploadfile);
    } else {

        $stmt = $pdo->prepare('UPDATE pet SET nomePet = :nomePet, nomeDono = :nomeDono, raca = :raca, idade = :idade WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nomePet', $nomePet);
        $stmt->bindParam(':nomeDono', $nomeDono);
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':idade', $idade);

    }
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    $pdo = null;
}
function edicao1()
{
    if (!isset($_POST["id"])) {
        header('Location: consulta.php');
    } else {

        $id = $_POST["id"];
        try {
            if (isset($_POST['salvar'])) {
                $id = $_POST['id'];
                $nomePet = $_POST["nomePet"];
                $nomeDono = $_POST['nomeDono'];
                $raca = $_POST['raca'];
                $idade = $_POST['idade'];
                $foto = $_FILES['foto'];

                editar($id, $nomePet, $nomeDono, $raca, $idade, $foto);
            }
            $stmt = buscarEdicao($id);

            while ($row = $stmt->fetch()) {

                $foto = $row['foto'];

                echo "<div class='container'>";

                echo "<form method='post' enctype='multipart/form-data'>\n";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<label for='nomePet'>Pet:</label>";
                echo "<input type='text' size='30' name='nomePet' value='$row[nomePet]'><br><br>\n";
                echo "<label>Nome do dono:</label>";
                echo "<input type='text' size='30' name='nomeDono' value='$row[nomeDono]'><br><br>\n";
                echo "<label>Raça:</label>";
                echo "<input type='text' size='30' name='raca' value='$row[raca]'><br><br>\n";
                echo "<label>Idade:</label>";
                echo "<input type='text' size='10' name='idade' value='$row[idade]'><br><br>\n";
                echo "<label>Foto do pet:</label>";
                if ($foto == null) {

                    echo "-<br><br>";
                } else {
                    echo "<img src=" . $foto . " width='50px' height='50px'><br><br>";
                }

                echo "<input type='file' name='foto'><br><br>";

                echo "<input type='submit' name='salvar' value='Salvar Alterações'>\n";

                echo "</form>";
                echo '</div>';

                if (isset($_POST['salvar'])) {
                    echo "<div class='container' style='background-color: #4CAF50; color: white; text-align: center; padding: 10px; border-radius: 5px;'>";
                    echo "Os dados do(a) $nomePet foram alterados!";
                    echo "</div>";
                }
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

?>