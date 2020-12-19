<?php
/* #############
    Arquivo:    exemplo.php
    Autor:      Rivaldo Sampaio de Oliveira Júnior, mestrando do PPG em Tecnologia, Gestão e Saúde Ocular da Unifesp
    Licença:    GNU-GPL
    Propósito:  O Propósito deste código fonte é exemplificar o consumo e uso dos dados do LattesService.
    Data:       02/jul/2020
############# */

    require_once 'cache.php';  
    require_once 'lattes_service_api.php';
    require_once 'producao_cientifica.php';
    require_once 'gerador_de_html.php';

    $endpoint = 'producao/orientador';
    $params   = array('2506247984223015'); //Código Lattes de 16 dígitos do orientador
    $filters  = array();
    $html     = '';
    
    $cache = new Cache();
    $cache->setArquivo($params, $filters);

    $isCacheExpirado = $cache->isCacheExpirado();
    
    if($isCacheExpirado){

		try {

			// Crie um objeto LattesServiceAPI para consumir os dados do LattesService
			$lattes_service = new LattesServiceAPI(); 
			$lattes_service->setEndpoint($endpoint);
			$lattes_service->setParams($params);
			$lattes_service->setFilters($filters);

			$response = $lattes_service->request();

		} catch (Exception $e) {

			$error_code = $e->getCode();
			$error_msg = $e->getMessage();

		}
	  
		if(!isset($error_code)){

              // Crie um objeto ProducaoCientifica para fazer uso dos dados obtidos
              $producao = new ProducaoCientifica();
              $producao->set($response);

              $geradorDeHTML = new GeradorDeHTML();
              $geradorDeHTML->setProducaoCientifica($producao);
              
              // Exiba as produções científicas que desejar
              // Neste exemplo estão sendo exibidas todos os tipos de produção que o orientador tem no Currículo Lattes
			  // Mas é possível selecionar apenas os tipos que deseja exibir. Exemplo:
              // $producao->incluirArtigosEmPeriodicosNoHtml();
              // $producao->incluirApresentacoesDeTrabalhosNoHtml();
              $geradorDeHTML->incluirTodasProducoesNoHtml();
              $html = $geradorDeHTML->gerarPaginaHtml();

              $cache->gravarCache($html,$response);

        }else{
              
			if($error_code == 204){
				$html = $cache->lerCache();
			}else{
				echo '[' . $error_code . '] ' . $error_msg;
			}
        }
	  
    }else{

      $html = $cache->lerCache();

    }
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <?php 

            echo $html;
          
        ?>
    </div>
  </body>
</html>