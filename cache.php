<?php 
/* #############
    Classe:     Cache
    Arquivo:    cache.php
    Autor:      Rivaldo Sampaio de Oliveira Júnior, mestrando do PPG em Tecnologia, Gestão e Saúde Ocular da Unifesp
    Licença:    GNU-GPL
    Propósito:  O Propósito desta classe é fazer o cache dos dados recebidos do LattesService.
    Data:       02/jul/2020
############# */


    class Cache{
        
        const CHAVE_DE_SEGURANCA = 'nu5hnJTW7woakCn';
        const ARQUIVO_CONTROLE_DE_EXPIRACAO = "expiracao.php";
        private $arquivo = '';
        private $dataDeExpiracao;

		
		public function setArquivo($params, $filters){
            
            if(!is_array($params) && count($params)==0){
                die('Parâmetro incorreto: $params. Deve ser um array com no mínimo um item.');
            }

            if(!is_array($filters)){
                die('Parâmetro incorreto: $filters. Deve ser um array.');
            }

            
			$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache';
            
			if (!file_exists($dir)) {
                if(!mkdir($dir,0775,true)) {
                    throw new Exception('Erro ao criar o diretório de cache. Verifique suas permissões de usuário ou crie o diretório manualmente', 2001);
                }
            }
			
			$dir .= DIRECTORY_SEPARATOR;

            $arquivo = $dir . $this::CHAVE_DE_SEGURANCA . '-';

            $arquivo .= implode('-', $params);


            if(count($filters)>0){
                $arquivo .= implode('=', $filters);
            }

            $arquivo .= '.php';
            
            $this->arquivo = $arquivo;

        }


        public function setDataDeExpiracao($dataDaProximaExtracao){

            $this->dataDeExpiracao = DateTime::createFromFormat('d/m/Y', $dataDaProximaExtracao);

        }


        public function lerCache(){
            
            if(!$this->temCache()) return '';
            
            $this->setDataDeExpiracao(file_get_contents($this::ARQUIVO_CONTROLE_DE_EXPIRACAO));
            
            return file_get_contents($this->arquivo);

        }


        public function gravarCache($html, $response){

            file_put_contents($this::ARQUIVO_CONTROLE_DE_EXPIRACAO, $response->proxima_extracao);

            file_put_contents($this->arquivo, $html);

        }


        public function temCache(){

            return file_exists($this->arquivo);

        }


        public function isCacheExpirado(){

            if(!$this->temCache()){ 
                return true;
            }

            $dataAtual = new DateTime();

            if($dataAtual >= $this->dataDeExpiracao){
                return true;
            }

            return false;
        }
		

    
    }

    