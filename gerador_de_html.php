<?php 
/* #############
    Classe:     GeradorDeHTML
    Arquivo:    gerador_de_html.php
    Autor:      Rivaldo Sampaio de Oliveira Júnior, mestrando do PPG em Tecnologia, Gestão e Saúde Ocular da Unifesp
    Licença:    GNU-GPL
    Propósito:  O Propósito desta classe é fornecer a produção científica já estruturada em HTML.
    Data:       03/dez/2020
############# */

    require_once 'producao_cientifica.php';


    class GeradorDeHTML{

        const BIBLIOGRAFICA = 'Produção Bibliográfica';
        const TECNICA       = 'Produção Técnica';
        const PATENTES      = 'Patentes e Registros';
        const ARTISTICA     = 'Produção Artística';

        private $producaoCientifica;
        private $tabelaTotalizadora     = array();
        private $listasDeProducaoEmHtml = array();


        function setProducaoCientifica(ProducaoCientifica $producaoCientifica = null){
            
            if(is_null($producaoCientifica) || !($producaoCientifica instanceof ProducaoCientifica) ){
                die('Informe um parâmetro válido ao instanciar o gerador de HTML');
            }

            $this->producaoCientifica = $producaoCientifica;

        }


        function montarTabelaTotalizadoraEmHtml(){

            $ano_atual = Date('Y');
            $cinco_anos_antes = $ano_atual - 4;
            
            $html = '<h3>Quadro Resumo - Totais de Produção Científica do(a) Orientador(a)</h3>';
            
            foreach ($this->tabelaTotalizadora as $categoria=>$totais){
                
                $html .= '<table class="table table-bordered">';
                // Cabeçalho da tabela
                $html .= '<thead class="thead-light">';
                $html .= '<tr class="text-center"><th colspan="8">' . $categoria . '</th></th>';
                $html .= '<tr class="text-center">';
                $html .= '<th>Tipo de Produção</th>';
                $html .= '<th>Anterior a ' . $cinco_anos_antes . '</th>';
                
                for ($ano=$cinco_anos_antes; $ano <= $ano_atual; $ano++){
                    $html .= '<th>' . $ano . '</th>';
                }

                $html .= '<th>Total</th>';
                $html .= '</tr>';
                $html .= '</thead>';

                // Corpo da tabela
                $html .= '<tbody>';
                
                foreach ($totais as $tr){
                    $html .= $tr;
                }

                $html .= '</tbody>';

                $html .= '</table>';
            }

            $html .= '<br />';
            $html .= '<br />';

            return $html;

        }


        function montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho){

            $html = '';

            if ($this->producaoCientifica->temProducao($objetos)){
                
                $contar_ano_a_ano = array_count_values(array_column($objetos,'ano'));

                $ano_atual = Date('Y');
                $cinco_anos_antes = $ano_atual - 4;

                // html deve ter uma <tr> com as seguintes <td>: tipo da produção | Anterior a 5 anos | Ano 1 | Ano 2 | Ano 3 | Ano 4 | Ano 5 (atual) | Total
                $html .= '<tr>';
                $html .= '<td>' . $cabecalho . '</td>';

                $soma_anterior_a_5_anos = 0;

                foreach ($contar_ano_a_ano as $ano=>$total){
                    if ($ano < $cinco_anos_antes){
                        $soma_anterior_a_5_anos += $total;
                    }
                }

                $html .= '<td class="text-center">';
                $html .= $soma_anterior_a_5_anos == 0 ? '-' : $soma_anterior_a_5_anos;
                $html .= '</td>';

                for ($ano=$cinco_anos_antes; $ano <= $ano_atual; $ano++){
                    $html .= '<td class="text-center">';
                    if (!array_key_exists($ano, $contar_ano_a_ano)){
                        $html .= '-';
                    }else{
                        $html .= $contar_ano_a_ano[$ano];
                    }
                    $html .= '</td>';
                }

                $html .= '<td class="text-center">' . array_sum($contar_ano_a_ano) . '</td>';

                $html .= '</tr>';

                $this->tabelaTotalizadora[$categoria][] = $html;
                
            }

        }


        function montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos){
            

            $html = '';

            if ($this->producaoCientifica->temProducao($objetos)){
                $html .= '<h4>' . $cabecalho . '</h4>';
                $html .= '<ol>';
                foreach($objetos as $producao){
                    $html .= '<li>';
                    foreach($atributos as $nome_atributo => $formatacao){
                        if(array_key_exists($nome_atributo, $producao)) {
                            $valor_atributo = $producao->{$nome_atributo};
                            if(!empty($valor_atributo)){
                                $html .= str_replace('{}', $valor_atributo, $formatacao);
                            }
                        }
                    }
                    $html .= '</li>';
                }
                $html .= '</ol>';

                $this->listasDeProducaoEmHtml[$categoria][] = $html;
            }

        }


        function montarListasComAsProducoesSelecionadasEmHtml(){
            
            $html = '';
            
            foreach($this->listasDeProducaoEmHtml as $categoria=>$listas){
                $html .= '<h3>' . $categoria . '</h3>';
                foreach($listas as $lista){
                    $html .= $lista;
                }
            }
            
            return $html;

        }


        function incluirApresentacoesDeTrabalhosNoHtml(){
            
            $objetos    = $this->producaoCientifica->getApresentacoesDeTrabalhos();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Apresentações de Trabalho';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'=>'<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'=>'{}. ',
                                'natureza'=>'{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirArtigosAceitosNoHtml(){

            $objetos    = $this->producaoCientifica->getArtigosAceitos();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Artigos Aceitos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'    => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'revista'   => '{}. ',
                                'volume'    => 'v. {}, ',
                                'numero'    => 'n. {}, ',
                                'paginas'   => 'p. {}, ',
                                'issn'      => 'issn: {}. ',
                                'ano'       => '{}. ',
                                'doi'       => '<a href="{}" target="_blank">{}</a>');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirArtigosEmPeriodicosNoHtml(){

            $objetos    = $this->producaoCientifica->getArtigosEmPeriodicos();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Artigos em Periódicos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'    => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'revista'   => '{}. ',
                                'volume'    => 'v. {}, ',
                                'numero'    => 'n. {}, ',
                                'paginas'   => 'p. {}, ',
                                'issn'      => 'issn: {}. ',
                                'ano'       => '{}. ',
                                'doi'       => '<a href="{}" target="_blank">{}</a>');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirCapitulosDeLivroPublicadosNoHtml(){

            $objetos    = $this->producaoCientifica->getCapitulosDeLivroPublicados();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Capítulos de Livros Publicados';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'    => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'livro'     => 'Em: {}. ',
                                'edicao'    => 'ed. {}',
                                'editora'   => ': {}. ',
                                'ano'       => '{}. ',
                                'volume'    => 'v. {}, ',
                                'paginas'   => 'p. {}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirLivrosPublicadosNoHtml(){

            $objetos    = $this->producaoCientifica->getLivrosPublicados();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Livros Publicados/Organizados ou Edições';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'    => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'edicao'    => 'ed. {}',
                                'editora'   => ': {}. ',
                                'ano'       => '{}. ',
                                'volume'    => 'v. {}, ',
                                'paginas'   => 'p. {}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirOutrosTiposDeProducaoBibliograficaNoHtml(){

            $objetos    = $this->producaoCientifica->getOutrosTiposDeProducaoBibliografica();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Outras Produções Bibliográficas';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'    => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'       => '{}. ',
                                'natureza'  => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);
            
        }


        function incluirResumosEmCongressoNoHtml(){

            $objetos    = $this->producaoCientifica->getResumosEmCongresso();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Resumos Publicados em Anais de Congressos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'nomeDoEvento'  => 'Em: {}, ',
                                'volume'        => 'v. {}, ',
                                'numero'        => 'n. {}, ',
                                'paginas'       => 'p. {}. ',
                                'ano'           => '{}. ',
                                'doi'           => '<a href="{}" target="_blank">{}</a>');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirResumosExpandidosEmCongressoNoHtml(){

            $objetos    = $this->producaoCientifica->getResumosExpandidosEmCongresso();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Resumos Expandidos Publicados em Anais de Congressos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'nomeDoEvento'  => 'Em: {}, ',
                                'volume'        => 'v. {}, ',
                                'paginas'       => 'p. {}. ',
                                'ano'           => '{}. ',
                                'doi'           => '<a href="{}" target="_blank">{}</a>');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }
        

        function incluirTextosEmJornaisDeNoticiaNoHtml(){

            $objetos    = $this->producaoCientifica->getTextosEmJornaisDeNoticia();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Textos em Jornais de Notícias/Revistas';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'nomeJornal'    => '{}, ',
                                'volume'        => 'v. {}, ',
                                'paginas'       => 'p. {}, ',
                                'data'          => '{}. ',
                                'ano'           => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirTrabalhosCompletosEmCongressoNoHtml(){

            $objetos    = $this->producaoCientifica->getTrabalhosCompletosEmCongresso();
            $categoria  = $this::BIBLIOGRAFICA;
            $cabecalho  = 'Trabalhos Completos Publicados em Anais de Congressos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'nomeDoEvento'  => 'Em: {}, ',
                                'volume'        => 'v. {}, ',
                                'paginas'       => 'p. {}. ',
                                'ano'           => '{}. ',
                                'doi'           => '<a href="{}" target="_blank">{}</a>');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirOutrosTiposDeProducaoTecnicaNoHtml(){

            $objetos    = $this->producaoCientifica->getOutrosTiposDeProducaoTecnica();
            $categoria  = $this::TECNICA;
            $cabecalho  = 'Demais Tipos de Produção Técnica';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ',
                                'natureza'      => '{}.');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirProcessosOuTecnicasNoHtml(){
            
            $objetos    = $this->producaoCientifica->getProcessosOuTecnicas();
            $categoria  = $this::TECNICA;
            $cabecalho  = 'Processos ou Técnicas';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ',
                                'natureza'      => '{}.');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }

        
        function incluirProdutosTecnologicosNoHtml(){

            $objetos    = $this->producaoCientifica->getProdutosTecnologicos();
            $categoria  = $this::TECNICA;
            $cabecalho  = 'Produtos Tecnológicos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirSoftwaresComPatenteNoHtml(){

            $objetos    = $this->producaoCientifica->getSoftwaresComPatente();
            $categoria  = $this::TECNICA;
            $cabecalho  = 'Programas de Computador com Registro';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirSoftwaresSemPatenteNoHtml(){

            $objetos    = $this->producaoCientifica->getSoftwaresSemPatente();
            $categoria  = $this::TECNICA;
            $cabecalho  = 'Programas de Computador sem Registro';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirTrabalhosTecnicosNoHtml(){

            $objetos    = $this->producaoCientifica->getTrabalhosTecnicos();
            $categoria  = $this::TECNICA;
            $cabecalho  = 'Trabalhos Técnicos';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirDesenhosIndustriaisNoHtml(){

            $objetos    = $this->producaoCientifica->getDesenhosIndustriais();
            $categoria  = $this::PATENTES;
            $cabecalho  = 'Desenhos Industriais';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'            => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'               => '{}. ',
                                'pais'              => '{}. ',
                                'numeroRegistro'    => '{}. ',
                                'dataDeposito'      => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirPatentesNoHtml(){

            $objetos    = $this->producaoCientifica->getPatentes();
            $categoria  = $this::PATENTES;
            $cabecalho  = 'Patentes';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'            => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'               => '{}. ',
                                'pais'              => '{}. ',
                                'numeroRegistro'    => '{}. ',
                                'dataDeposito'      => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirProgramasDeComputadorNoHtml(){

            $objetos    = $this->producaoCientifica->getProgramasDeComputador();
            $categoria  = $this::PATENTES;
            $cabecalho  = 'Programas de Computador';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'            => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'               => '{}. ',
                                'pais'              => '{}. ',
                                'numeroRegistro'    => '{}. ',
                                'dataDeposito'      => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }


        function incluirProducoesArtisticasNoHtml(){

            $objetos    = $this->producaoCientifica->getProducoesArtisticas();
            $categoria  = $this::PATENTES;
            $cabecalho  = 'Produções Artísticas';
            $atributos  = array('autores'=>'{}. ',
                                'titulo'        => '<span class="titulo_producao"><strong>{}</strong></span>. ',
                                'ano'           => '{}. ');
            
            $this->montarListaPorTipoDeProducaoEmHtml($objetos, $categoria, $cabecalho, $atributos);
            $this->montarLinhaTotalizadoraEmHtml($objetos, $categoria, $cabecalho);

        }
  

        function incluirTodasProducoesNoHtml(){
            /* #############
                Tentou-se seguir a mesma ordem das produções apresentadas no Currículo Lattes
            ############# */

            // Produções Bibliográficas
            $this->incluirArtigosEmPeriodicosNoHtml();
            $this->incluirLivrosPublicadosNoHtml();
            $this->incluirCapitulosDeLivroPublicadosNoHtml();
            $this->incluirTextosEmJornaisDeNoticiaNoHtml();
            $this->incluirTrabalhosCompletosEmCongressoNoHtml();
            $this->incluirResumosExpandidosEmCongressoNoHtml();
            $this->incluirResumosEmCongressoNoHtml();
            $this->incluirArtigosAceitosNoHtml();
            $this->incluirApresentacoesDeTrabalhosNoHtml();
            $this->incluirOutrosTiposDeProducaoBibliograficaNoHtml();
        
            // Produções Técnicas    
            $this->incluirSoftwaresSemPatenteNoHtml();
            $this->incluirSoftwaresComPatenteNoHtml();
            $this->incluirProcessosOuTecnicasNoHtml();
            $this->incluirProdutosTecnologicosNoHtml();
            $this->incluirTrabalhosTecnicosNoHtml();
            $this->incluirOutrosTiposDeProducaoTecnicaNoHtml();

            // Patentes e Registros
            $this->incluirPatentesNoHtml();
            $this->incluirDesenhosIndustriaisNoHtml();
            $this->incluirProgramasDeComputadorNoHtml();
            
            // Produções Artísticas
            $this->incluirProducoesArtisticasNoHtml();

        }


        public function gerarPaginaHtml(){
            
            $html = "";

            $html .= $this->montarTabelaTotalizadoraEmHtml();

            $html .= $this->montarListasComAsProducoesSelecionadasEmHtml();

            return $html;

        }
    }

