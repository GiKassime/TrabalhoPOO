<?php 
function adicionaArquivos($array){
    foreach ($array as $nome) {
        require_once 'models/'.$nome.'.php'; // criei essa função pra n ficar dando varios requires once, somente isso
    }
}
function cadastraMusica(){
    $nome = readline("🎵 Nome da música: ");
    $artista = readline("🎤 Nome do artista: ");
    $album = readline("💿 Nome do álbum: ");
    do {
        $duracao = readline("⏳ Duração da música (min:seg): ");
    } while (!preg_match('/^\d{1,2}:\d{1,2}$/', $duracao));// usei essa bomba ai pra verificar se digitou no formato 00:00 '/^\d{1,2} pra ver se ele digitou os minutos com 1 ou 2 casas, ai o : pra ver se ele separou usando os : e dps a msm coisa pros segundos, só n verifiquei se passar de 60  ou algo assim
    $duracao = str_replace(":", ".", $duracao);//aqui to convertendo para que na classe armazene como float porem na hora de mostrar para o usuário eu substituo com : ai n preciso armazenar minutos e segundos separadamente, e caso eu queira somar futuramente eu posso pegar oq tem antes e depois do . do float 
    echo "\nGÊNEROS\n1 - 🎸 Rock\n2 - 🎤 Pop\n3 - BR Nacional\n4 - 🤠 Sertanejo\n5 - 🎧 Outro\n";
    do {
        $tipo = readline("Qual o gênero da música? ");
    } while($tipo <= 0 || $tipo > 5);
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
            $musica = new Outro($nome,$duracao,$artista,$album,false);
            $musica->setNomeTipo(readline("📌 Nome desse outro Gênero?: "));
            return $musica;
            break;
        
    } 
}
function excluiMusica($arrayMusica){
    if (empty($arrayMusica)) {
        echo "❌ Nenhuma música cadastrada no momento.\n";
        return $arrayMusica; //aqiu retorna a lista original
    }else{
        do {
            listarMusicas($arrayMusica);
            echo "\n";
            $musica = readline("🗑️ Qual o índice da música que deseja excluir?: ");
        }while ($musica <= 0 || $musica > count($arrayMusica));
        array_splice($arrayMusica,($musica - 1));
        echo "\n✅ Música excluída com sucesso!\n";
        return $arrayMusica;
    }
    
}
function listarMusicas($arrayMusica){
    echo "\nLISTA DE MÚSICAS\n";
    if (empty($arrayMusica)) {
        echo "❌ Nenhuma música cadastrada no momento.\n";
        return false; // Retorna falso  por conta la da parte de excluir musicas que aproveito
    }
    foreach ($arrayMusica as $key => $musica) {
        $dados = "\n🎵 " .($key + 1)." - ".$musica;
        if ($musica instanceof Sertanejo) {
            $dados.= " | Gênero: Sertanejo";
        }elseif ($musica instanceof Pop) {
            $dados.= " | 🎶 Gênero: Pop";
        }elseif ($musica instanceof Rock) {
            $dados.= " |🎶 Gênero: Rock";
        }elseif ($musica instanceof Nacional) {
            $dados.= " |🎶 Gênero: Nacional";
        }else{
            $dados .= " |🎶 Gênero : ". $musica->getNomeTipo();
        }
        if($musica->isStatus()){
            $dados.= " 💿 TOCANDO 🎶";
        }
        echo $dados;
    }
    return true;
}
function playerMusica($arrayMusica){
    if (empty($arrayMusica)) {
        echo "❌ Nenhuma música cadastrada no momento, cadastre uma música para utiliza o player.\n";
        return false; 
    }
    do {
        $musicaTocando = temMusicaTocando($arrayMusica);
        echo "\n\n====================PLAYER DE MÚSICA====================\n\n";
        echo "\n🎵 Música Tocando:";
        echo  !$musicaTocando ? "Nenhuma música tocando\n" :$musicaTocando->getNome() ." de ".$musicaTocando->getArtista()."\n";
        echo "\n1 - ⏪ Anterior\n2 - ⏸️ Pausar\n3 - ▶️ Tocar\n4 - ⏩ Avançar\n5- ☰ Reprodução\n0- ❌Sair\n\n";
        $acao = readline("➡️ Escolha uma opção: ");
        switch ($acao) {
            case 1:
                //ANTERIOR
                $musicaTocando = mudarMusica($arrayMusica,$musicaTocando,"anterior");
                break;
            case 2:
                //PAUSAR
                if ($musicaTocando) {
                    echo $musicaTocando->pausarMusica();  // Pausa a música
                    $musicaTocando = temMusicaTocando($arrayMusica);//como n vai ter nenhuma musica tocando ai vai receber false aqui
                } else {
                    echo "❌ Nenhuma música está tocando no momento.\n";
                }
                break;
            case 3:
                //TOCAR
                listarMusicas($arrayMusica);
                echo "\n";
                do {
                    $tocar = readline("Escolha uma música para tocar: ");
                } while ($tocar <= 0 || $tocar > count($arrayMusica));
                if (is_object($musicaTocando)) {
                    echo $musicaTocando->pausarMusica();//pausa a música q tava tocando antes
                } 
                $musicaTocando = $arrayMusica[($tocar-1)];//começa a tocar a música q o usuário escolheu
                echo $musicaTocando->tocarMusica();
                break;
            case 4:
                //AVANÇAR
                $musicaTocando = mudarMusica($arrayMusica,$musicaTocando,"avancar");
                break;
            case 5:
                //REPRODUÇÃO
                listarMusicas($arrayMusica);
                break;
            case 0:
                echo "\n❌ Saindo do player de música...\n";
                if (is_object($musicaTocando)) {
                    echo $musicaTocando->pausarMusica();//pausa se tiver alguma música toacando
                }
                break;
            default:
                echo "\n❌ Opção inválida. Tente novamente.\n";
                break;
        }
    } while ($acao != 0);
}
function temMusicaTocando($arrayMusica){
    foreach ($arrayMusica as $musica) {
        if ($musica->isStatus()) {
            return $musica; 
        }
    }
    return false;
}
function mudarMusica($arrayMusica,$musicaAtual,$funcao){
    if (!$musicaAtual) {
        echo "Coloque uma música para tocar para usar esta funcão!";
        return $musicaAtual;
    }
    $indiceAtual = array_search($musicaAtual, $arrayMusica); //quero achar o indice de onde ta a musica tocando dentro do array
    echo $musicaAtual->pausarMusica();
    if ($funcao == "avancar") {
        if ($indiceAtual + 1 >= count($arrayMusica) ) {//se ao ultrapassar a quantidade de músicas, volta para a primeira música
            echo "\nVoltamos para o inicio da playist 🙂";
            $musicaAtual = $arrayMusica[0];
        }else{
            $musicaAtual = $arrayMusica[$indiceAtual+1];
        }
    }elseif ($funcao == "anterior") {
        if ($indiceAtual - 1 < 0 ) { //se ao n tiver maias nenhuma musica antes, volta para a última música
            echo "\nVoltamos para a última música da playist 🙂";
            $musicaAtual = $arrayMusica[count($arrayMusica)-1];
        }else{
            $musicaAtual = $arrayMusica[$indiceAtual-1];
        }
    }
    echo $musicaAtual->tocarMusica();
    return $musicaAtual;
}
$arquivos = array("Sertanejo","Nacional","Rock","Outro","Pop");//ai coloco os nomes das classes que vou adicionar ao codigo
adicionaArquivos($arquivos);
$arrayMusicas = array();

//MENU

do {
    echo "\n========================= SPOOtify =========================\n  1 - 🎵 Cadastrar música\n  2 - 📜 Listar músicas\n  3 - 🗑️ Excluir música\n  4 - 🎧 Player\n  0 - ❌ Sair do SPOOtify :(\n============================================================\n";
    $resposta = readline("➡️ Escolha uma opção: ");
    echo "\n";
    switch ($resposta) {
        case 1:
            //CADASTRO DE MÚSICA
            array_push($arrayMusicas, cadastraMusica());
            echo "\n✅ Música cadastrada com sucesso!\n";
            break;
        case 2:
            //LISTA DE MÚSICA
            listarMusicas($arrayMusicas);
            break;
        case 3:
            //EXCLUIR DE MÚSICA
            $arrayMusicas = excluiMusica($arrayMusicas);
            break;
        case 4:
            //PLAYER DE MÚSICA
            playerMusica($arrayMusicas);
            break;
        case 0:
            echo "🎵 Obrigado por usar o SPOOtify! Até logo! 🎵\n";
            break;
        default:
            echo "❌ Opção inválida. Tente novamente.\n";
            break;
    }
    
} while ($resposta != 0);
?>