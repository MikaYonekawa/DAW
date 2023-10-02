
<?php 
    function escreve(){
    $filename = fopen("log.txt",'a+');//pediu para abrir o arquivo para leitura e escrita
    //w+ sobrescreve; a+ ele adiciona: mais de uma linha
    fwrite($filename,"\n mika");//date("Y-m-d H:i:s"));//escreve algo dentro do arquivo, usando função date
    fclose($filename);//fechar o arquivo
    echo "Arquivo criado com sucesso\n";
    echo filesize("log.txt");
    }
    function ler(){
        $filename = "mika.txt";

        $handle = fopen($filename, "r");//r - leitura 
        $conteudo = fread($handle,filesize($filename));

        echo $conteudo;
        fclose($handle);
    }
    
    function lerLinha(){
        //lê o conteudo e traz linha a linha
        //func file cria um array, cada posicao é uma linha do arquivo

        $arq = file("alunos.txt");
        for($i=0;$i<count($arq); $i++
        ){
            echo "linha ".$i. ":".$arq[$i]. "<br>";
        }

    }
    function fileGet(){
        // é mais pratico, abre e fecha, mas mostra a mesma coisa
        
        //echo file_get_contents("alunos.txt");

        // essa mesma função funciona se passar uma url 
        echo file_get_contents('https://www.cotil.unicamp.br/');
    }
    function abrirArq(){
        
        $home = file_get_contents('https://www.cotil.unicamp.br/');
        $filename = fopen("cotil.php",'w+');
        fwrite($filename,$home);
        fclose($filename);
        echo 'Arquivo criado com sucesso';
    }
    function csv(){
        include('CRUDBasico/conexaoBD.php');

        try{
            $stmt = $pdo->prepare("SELECT * FROM alunos ORDER BY ra");
            $stmt->execute();

            $fp = fopen('arqAlunos.csv','w');
            $colunasTitulo = array("ra","nome","curso");

            fputcsv($fp,$colunasTitulo);

            while($row = $stmt->fetch()){
                $ra = $row['ra'];
                $nome = $row['nome'];
                $curso = $row['curso'];

                $lista = array(
                    array($ra,$nome,$curso)
                );

                foreach($lista as $linha){
                    fputcsv($fp,$linha);
                }
            }
                $msg = "Arquivo criado: arqAlunos.csv";
                fclose($fp);
        }catch(PDOException $e){
                echo 'Error' . $e->getMessage();
            }
            echo $msg;
        

        }
        //falta manipulação de pastas, excluir e renomear
        //depois vai ensinar a partir de um form, fazer um upload de arquivos 
    


?> 