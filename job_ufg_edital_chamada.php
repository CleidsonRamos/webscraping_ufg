<?php

echo "<h1>Verifica se foi cadastrado um novo edital de chamada</h1></br>";
echo "<hr></hr>"; //linha

    // Carrega a biblioteca simple_html_dom library
    include_once('../public_html/wp-includes/simple_html_dom.php');
    
    // Define o horario de São Paulo
    date_default_timezone_set('America/Sao_Paulo');
    
    // Função para registrar um log
    function logMe($msg){
        $fp = fopen('../public_html/job_ufg_log_edital_chamada.txt', "a");
        
        // Escreve a mensagem passada através da variável $msg
        $escreve = fwrite($fp, $msg."\n");
        
        // Fecha o arquivo
        fclose($fp);
    }
    
    //pega a data atual para fins de log
    $hoje = date('d/m/Y H:i:s');
    
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
        echo "<b>Encontrei </b>".$name_to_search." - ".$hoje."</br>";
        logMe("Encontrei ".$name_to_search." - ".$hoje." </br>");

        //preenche o formulario de contato
        $url = 'https://cleidson.dev/';

        // Dados do formulário
        $data = array(
            'wpforms[fields][0]' => 'Novo edital de chamada cadastrado',
            'wpforms[fields][1]' => 'cleidson.ramosmartins@gmail.com',
            'wpforms[fields][2]' => 'Um novo edital de chamada foi cadastrado acesse para verificar https://propessoas.ufg.br/p/23924-editais-de-chamada-tecnicos-administrativos </br> Confira aqui a documentação: https://propessoas.ufg.br/p/30911-documentos-formularios-e-orientacoes-para-admissao',
            'wpforms[id]'=> 257,
            'wpforms[nonce]' => 'ac2a107bdb',
            'wpforms[author]' => 1,
            'wpforms[post_id]' => 19,
            'wpforms[submit]' => 'wpforms-submit',
            'wpforms[token]' => 'insira o token aqui',
            'action' => 'wpforms_submit',
            'page_url'=> 'https//cleidson.dev/',
            'page_title' => 'Home',
            'page_id'=> '19'
        );
        
        // Inicializa o cURL
        $ch = curl_init();
        
        // Configura as opções do cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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

    } else {
        echo "Não encontrei ".$name_to_search." - ".$hoje." </br>";
        logMe("Não encontrei ".$name_to_search." - ".$hoje." </br>");
    }
