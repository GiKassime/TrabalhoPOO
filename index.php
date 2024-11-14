<?php 
function adicionaArquivos($array){
    foreach ($array as $nome) {
        require_once 'models/'.$nome.'.php'; // criei essa função pra n ficar dando varios requires once, somente isso
    }
}
function cadastraMusica(){
    $nome = readline("Qual o nome da música?: ");
    $artista = readline("Qual o nome do artista?: ");
    $album = readline("Qual o album da música?: ");
    $duracao = readline("Qual a duracao da música?: ");
    echo "\nGÊNEROS\n1 - Rock\n2 - Pop\n3 - Nacional\n4 - Sertanejo\n5 - Outro";
    do {
        $tipo = readline("Qual o tipo da música? ");
    } while($tipo <= 0 && $tipo > 5);
    switch ($tipo) {
        case 1:
            return new Rock($nome,$duracao,$artista,$album,false);//coloquei o status como false pra musica ja estar pausada quando criar
            break;
        case 2:
            return new Pop($nome,$duracao,$artista,$album,false);
            break;
        case 3:
            return new Nacional($nome,$duracao,$artista,$album,false);
            break;
        case 4:
            return new Sertanejo($nome,$duracao,$artista,$album,false);
            break;  
        case 5:
            $outro = new Outro($nome,$duracao,$artista,$album,false);
            $outro->setNomeTipo(readline("Qual o nome desse outro tipo?: "));
            return $outro;
            break;
        
    }
    return new Musica($nome, $duracao,$artista,$album,false);
}
function excluiMusica($arrayMusica){
    
}
function listarMusicas($arrayMusica){
    foreach ($arrayMusica as $key => $musica) {
        $dados = "\n" .($key + 1)." - ".$musica;
        if ($musica instanceof Sertanejo) {
            $dados.= " | Tipo: Sertanejo";
        }elseif ($musica instanceof Pop) {
            $dados.= " | Tipo: Pop";
        }elseif ($musica instanceof Rock) {
            $dados.= " | Tipo: Rock";
        }elseif ($musica instanceof Nacional) {
            $dados.= " | Tipo: Nacional";
        }else{
            $dados .= " | Tipo : ". $musica->getNomeTipo();
        }
        echo $dados;
    }
}

$arquivos = array("Sertanejo","Nacional","Rock","Outro","Pop");//ai coloco os nomes das classes que vou adicionar ao codigo
adicionaArquivos($arquivos);
$arrayMusicas = array();

//MENU

do {
    echo "\nBem vindo ao SPOOtify";
    echo "\n1 - Cadastrar musica\n2 - Listar Músicas\n3 - Excluir Música\n4 - Player\n0 - Sair do SPOOtify :(\n";
    $resposta = readline("Escolha uma opção:");
    switch ($resposta) {
        case 1:
            //CADASTRO DE MÚSICA
            array_push($arrayMusicas,cadastraMusica());
            break;
        case 2:
            //LISTA DE MÚSICA
            listarMusicas($arrayMusicas);
            break;
        case 3:
            //EXCLUIR DE MÚSICA
            excluiMusica($arrayMusicas);
            
            break;
        case 4:
            //PLAYER DE MÚSICA
            
            break;
        
        default:
            echo "\nResposta inváldia";
            break;
    }
} while ($resposta != 0);
?>