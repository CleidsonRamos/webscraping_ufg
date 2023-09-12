<?php

    // Carrega a biblioteca simple_html_dom library
    include_once('../public_html/wp-includes/simple_html_dom.php');
    
    function send($metodo, $parametros){
        
        echo' estou dentro do send</br>';
        
        $bot_token = "coloque o token aqui";
        $url = "https://api.telegram.org/bot".$bot_token."/sendMessage";
        
        if(!$ch = curl_init()){
        echo' exit';
            exit();
        }
        
        // Configura as opções do cURL
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);
        
        // Verifica se houve algum erro
        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        } else {
            echo 'Requisição enviada com sucesso!<br>';
            //echo 'Resposta do servidor:<br>';
            //echo $response;
        }
        
        // Fecha o cURL
        curl_close($ch);
    }
    
    // Acessa a pagina que vai ser feito o webscraping
    $url = 'https://propessoas.ufg.br/p/23924-editais-de-chamada-tecnicos-administrativos';
    
    // Nome que vai ser pesquisado
    $name_to_search = 'Edital de Chamada 17/2023 - Técnico Administrativo';
    
    // Pega todo conteudo html
    $html = file_get_html($url);
    
    $pageContent = file_get_contents($url);

    // Pesquisa pelo nome
    $found = false;
    // foreach($html->find('td') as $element) {
    //     if (stripos($element->plaintext, $name_to_search) !== false) {
    //         $found = true;
    //         break;
    //     }
    // }

    if (strpos($pageContent, $name_to_search) !== false) {
        $found = true;
        echo 'O texto foi encontrado na página.';
    }

    // Mostra o resultado da busca
    if ($found) {
        
        $parametros = array(
            "chat_id" => '-1001816973949', //grupo
            //"chat_id" => '285414699', //cleidson_r
            "text" => 'Teste O texto "'.$name_to_search.'" foi encontrado na página.'
        );
    
        //Envia mensagem para o telegram
        send("sendMessage", $parametros);        

    } else {
        echo "Não encontrei ".$name_to_search." - ".$hoje." </br>";
    }
