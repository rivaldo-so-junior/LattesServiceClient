<?php 
/* #############
    Classe:     LattesServiceAPI
    Arquivo:    lattes_service_api.php
    Autor:      Rivaldo Sampaio de Oliveira Júnior, mestrando do PPG em Tecnologia, Gestão e Saúde Ocular da Unifesp
    Licença:    GNU-GPL
    Propósito:  O Propósito desta classe é consumir os dados do webservice LattesService.
    Data:       02/jul/2020
############# */


    //require_once 'cache.php';

    class LattesServiceAPI{
        const DOMINIO = 'ls.home.br'; // Substitua pelo domínio onde está hospedado seu LattesService.
        private $endpoint;
        private $params;
        private $filters;

        //public function __construct($endpoint, $params, $filters=array()){
            /*
                $endpoint - Obrigatório. 
                            Indica o recurso a ser consumido. 
                            No momento do lançamento deste código haviam dois: producao/orientador e producao/producao_por_tipo

                $params   - Obrigatório.
                            São os parâmetros necessários para a busca dos dados no endpoint.
                            Para o endpoint producao/orientador, o parâmetro esperado é o código Lattes de 16 dígitos.
                                Exemplo: array('id_lattes'=>'1234567890123456') irá retornar os dados do orientador que tem o código Lattes 1234567890123456.
                            Para o endpoint producao/producao_por_tipo são esperados dois parâmetros: tipo da produção e código Lattes de 16 dígitos.
                                Exemplo: array('tipo'=>'artigos_em_periodicos', 'id_lattes'=>'1234567890123456') irá retornar os artigos em periódicos do 
                                        orientador que tem o código Lattes 1234567890123456.
                                Para consultar os tipos possíveis, acesse no navegador a url api/docs.

                $filters  - Opcional.
                            São parâmetros adicionais para refinar o resultado.
                            Para os endpoints producao/orientador e producao/producao_por_tipo, o filtro disponível é a_partir_de. Serve para filtrar as produções.
                            "a partir do ano" desejado. 
                            Exemplo: array('a_partir_de'=>'2015') irá retornar as produções a partir de 2015.
            */
        //    $this->setEndpoint($endpoint);
        //    $this->setParams($params);
        //    $this->setFilters($filters);
        //}


        function request(){
            
            // Caso não tenha cache, inicia e configura uma sessão cURL
            $ch = curl_init($this->getUrl());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Requisita os dados ao webservice
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Retorna os dados ou lança uma exceção conforme o código do status HTTP retornado pelo webservice
            if($status == 200) {

                return json_decode($response); ;
            }

            // Se retornar 204 é porque ainda não foi realizada uma nova extração do Lattes, logo 
            // há cache no client e deverá obrigatoriamente ser utlizado
            if($status == 204) throw new Exception('Utilizar, obrigatoriamente, o cache.', $status);

            if($status == 400) throw new Exception('Parâmetros Inválidos.', $status);

            if($status == 403) throw new Exception('Acesso negado. Contate o administrador do servidor.', $status);

            if($status == 404) throw new Exception('Dados não localizados.', $status);

            if($status == 500) throw new Exception('Servidor indisponível. Contate o administrador do servidor.', $status);

        }


        function setEndpoint($endpoint){

            if (!is_string($endpoint)) throw new Exception('O endpoint deve ser uma string. Verifique se o informou corretamente.', 1001);

            if (empty($endpoint) || trim($endpoint)=='') throw new Exception('O endpoint não pode ser nulo. Informe o endpoint.', 1002);

            $this->endpoint = $endpoint;

        }


        function setParams($params){

            if (!is_array($params) || count($params)==0) throw new Exception('Erro ao ler os parâmetros. Verifique se os informou corretamente.', 1003);

            foreach($params as $param){
                if(empty($param)) continue;
                $this->params .= urlencode($param) .  '/';
            }

        }


        function setFilters($filters){

            if (!is_array($filters)) throw new Exception('Erro ao ler os filtros. Verifique se os informou corretamente.', 1004);

            if(count($filters)>0){
                foreach($filters as $filter=>$param){
                    if(empty($filter)) continue;
                    $this->filters .= '&' .  urlencode($filter) . '=' . urlencode($param);
                }
            }

        }


        function getUrl(){

            $url = 'http://' . $this::DOMINIO . '/api/';
            $url .= $this->endpoint . '/'; 
            $url .= $this->params;
            $url .= '?format=json';
            $url .= $this->filters;

            return $url;
        }


    }

	