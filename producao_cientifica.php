<?php 
/* #############
    Classe:     ProducaoCientifica
    Arquivo:    producao_cientifica.php
    Autor:      Rivaldo Sampaio de Oliveira Júnior, mestrando do PPG em Tecnologia, Gestão e Saúde Ocular da Unifesp
    Licença:    GNU-GPL
    Propósito:  O Propósito desta classe é fornecer a produção científica já estruturada em HTML.
    Data:       02/jul/2020
############# */


    class ProducaoCientifica{

        private $apresentacoesDeTrabalhos           = array();
        private $artigosAceitos                     = array();
        private $artigosEmPeriodicos                = array();
        private $capitulosDeLivroPublicados         = array();
        private $livrosPublicados                   = array();
        private $outrosTiposDeProducaoBibliografica = array();
        private $resumosEmCongresso                 = array();
        private $resumosExpandidosEmCongresso       = array();
        private $textosEmJornaisDeNoticia           = array();
        private $trabalhosCompletosEmCongresso      = array();
        private $outrosTiposDeProducaoTecnica       = array();
        private $processosOuTecnicas                = array();
        private $produtosTecnologicos               = array();
        private $softwaresComPatente                = array();
        private $softwaresSemPatente                = array();
        private $trabalhosTecnicos                  = array();
        private $producoesArtisticas                = array();
        private $desenhosIndustriais                = array();
        private $patentes                           = array();
        private $programasDeComputador              = array();


        function set($dadosJSON = null){
            /* ###################
            # $dadosJSON deve conter um array de objetos obtidos com a classe LattesServiceAPI.php
            ################### */

            if (!is_null($dadosJSON)) {
                if(array_key_exists('apresentacoes_de_trabalhos',$dadosJSON)) $this->apresentacoesDeTrabalhos = $dadosJSON->apresentacoes_de_trabalhos;
                if(array_key_exists('artigos_aceitos',$dadosJSON)) $this->artigosAceitos = $dadosJSON->artigos_aceitos;
                if(array_key_exists('artigos_em_periodicos',$dadosJSON)) $this->artigosEmPeriodicos = $dadosJSON->artigos_em_periodicos;
                if(array_key_exists('capitulos_de_livro_publicados',$dadosJSON)) $this->capitulosDeLivroPublicados = $dadosJSON->capitulos_de_livro_publicados;
                if(array_key_exists('livros_publicados',$dadosJSON)) $this->livrosPublicados = $dadosJSON->livros_publicados;
                if(array_key_exists('outros_tipos_de_producao_bibliografica',$dadosJSON)) $this->outrosTiposDeProducaoBibliografica = $dadosJSON->outros_tipos_de_producao_bibliografica;
                if(array_key_exists('resumos_em_congresso',$dadosJSON)) $this->resumosEmCongresso = $dadosJSON->resumos_em_congresso;
                if(array_key_exists('resumos_expandidos_em_congresso',$dadosJSON)) $this->resumosExpandidosEmCongresso = $dadosJSON->resumos_expandidos_em_congresso;
                if(array_key_exists('textos_em_jornais_de_noticia',$dadosJSON)) $this->textosEmJornaisDeNoticia = $dadosJSON->textos_em_jornais_de_noticia;
                if(array_key_exists('trabalhos_completos_em_congresso',$dadosJSON)) $this->trabalhosCompletosEmCongresso = $dadosJSON->trabalhos_completos_em_congresso;
                if(array_key_exists('outros_tipos_de_producao_tecnica',$dadosJSON)) $this->outrosTiposDeProducaoTecnica = $dadosJSON->outros_tipos_de_producao_tecnica;
                if(array_key_exists('processos_ou_tecnicas',$dadosJSON)) $this->processosOuTecnicas = $dadosJSON->processos_ou_tecnicas;
                if(array_key_exists('produtos_tecnologicos',$dadosJSON)) $this->produtosTecnologicos = $dadosJSON->produtos_tecnologicos;
                if(array_key_exists('softwares_com_patente',$dadosJSON)) $this->softwaresComPatente = $dadosJSON->softwares_com_patente;
                if(array_key_exists('softwares_sem_patente',$dadosJSON)) $this->softwaresSemPatente = $dadosJSON->softwares_sem_patente;
                if(array_key_exists('trabalhos_tecnicos',$dadosJSON)) $this->trabalhosTecnicos = $dadosJSON->trabalhos_tecnicos;
                if(array_key_exists('producoes_artisticas',$dadosJSON)) $this->producoesArtisticas = $dadosJSON->producoes_artisticas;
                if(array_key_exists('desenhos_industriais',$dadosJSON)) $this->desenhosIndustriais = $dadosJSON->desenhos_industriais;
                if(array_key_exists('patentes',$dadosJSON)) $this->patentes = $dadosJSON->patentes;
                if(array_key_exists('programas_de_computador',$dadosJSON)) $this->programasDeComputador = $dadosJSON->programas_de_computador;
            }

            
        }


        public function getApresentacoesDeTrabalhos(){
            return $this->apresentacoesDeTrabalhos;
        }


        public function getArtigosAceitos(){
            return $this->artigosAceitos;
        }


        public function getArtigosEmPeriodicos(){
            return $this->artigosEmPeriodicos;
        }


        public function getCapitulosDeLivroPublicados(){
            return $this->capitulosDeLivroPublicados;
        }


        public function getLivrosPublicados(){
            return $this->livrosPublicados;
        }


        public function getOutrosTiposDeProducaoBibliografica(){
            return $this->outrosTiposDeProducaoBibliografica;
        }


        public function getResumosEmCongresso(){
            return $this->resumosEmCongresso;
        }


        public function getResumosExpandidosEmCongresso(){
            return $this->resumosExpandidosEmCongresso;
        }


        public function getTextosEmJornaisDeNoticia(){
            return $this->textosEmJornaisDeNoticia;
        }


        public function getTrabalhosCompletosEmCongresso(){
            return $this->trabalhosCompletosEmCongresso;
        }


        public function getOutrosTiposDeProducaoTecnica(){
            return $this->outrosTiposDeProducaoTecnica;
        }


        public function getProcessosOuTecnicas(){
            return $this->processosOuTecnicas;
        }


        public function getProdutosTecnologicos(){
            return $this->produtosTecnologicos;
        }


        public function getSoftwaresComPatente(){
            return $this->softwaresComPatente;
        }


        public function getSoftwaresSemPatente(){
            return $this->softwaresSemPatente;
        }


        public function getTrabalhosTecnicos(){
            return $this->trabalhosTecnicos;
        }


        public function getProducoesArtisticas(){
            return $this->producoesArtisticas;
        }


        public function getDesenhosIndustriais(){
            return $this->desenhosIndustriais;
        }


        public function getPatentes(){
            return $this->patentes;
        }


        public function getProgramasDeComputador(){
            return $this->programasDeComputador;
        }


        function temProducao($arrayDeObjetos){
            
            if(count($arrayDeObjetos) > 0) {
                return true;
            }

            return false;

        }


        function temProducaoBibliografica(){
            
            if ($this->temProducao($this->apresentacoesDeTrabalhos))           return true; 
            if ($this->temProducao($this->artigosAceitos))                     return true;
            if ($this->temProducao($this->artigosEmPeriodicos))                return true;
            if ($this->temProducao($this->capitulosDeLivroPublicados))         return true;
            if ($this->temProducao($this->livrosPublicados))                   return true;
            if ($this->temProducao($this->outrosTiposDeProducaoBibliografica)) return true;
            if ($this->temProducao($this->resumosEmCongresso))                 return true;
            if ($this->temProducao($this->resumosExpandidosEmCongresso))       return true;
            if ($this->temProducao($this->textosEmJornaisDeNoticia))           return true;
            if ($this->temProducao($this->trabalhosCompletosEmCongresso))      return true;

            return false;

        }


        function temProducaoTecnica(){
            
            if ($this->temProducao($this->outrosTiposDeProducaoTecnica))   return true; 
            if ($this->temProducao($this->processosOuTecnicas))            return true;
            if ($this->temProducao($this->produtosTecnologicos))           return true;
            if ($this->temProducao($this->softwaresComPatente))            return true;
            if ($this->temProducao($this->softwaresSemPatente))            return true;
            if ($this->temProducao($this->trabalhosTecnicos))              return true;

            return false;

        }


        function temPatentesERegistros(){
            
            if ($this->temProducao($this->desenhosIndustriais))    return true; 
            if ($this->temProducao($this->patentes))               return true; 
            if ($this->temProducao($this->programasDeComputador))  return true; 

            return false;

        }

        
        function temProducaoArtistica(){
            
            if ($this->temProducao($this->producoesArtisticas))   return true;

            return false;

        }


    }
        
