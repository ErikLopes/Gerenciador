<?php
    include_once(dirname(__DIR__).'/model/Fatura.php');
    include_once(dirname(__DIR__).'/model/Itemfatura.php');
    include_once(dirname(__DIR__).'/model/Conexao.php');
    include_once(dirname(__DIR__).'/model/Produto.php');

    $acao = $_REQUEST['acao'];

    if($acao != ''){
        $itemFaturaController = new ItemFaturaController();
        switch($acao){
            case 'cadastrar':
               $itemFaturaController->adicionarItemFatura($_POST); 
                break;
            case 'editar':
                $itemFaturaController->editarFatura($_POST);
                break;
            case 'excluir':
                return $itemFaturaController->excluirItemFatura($_POST);
                break;  
        }
    }

class ItemFaturaController{
        
    public function adicionarItemFatura($dados){
        $conexao = new Conexao();
        $itemFatura = new Itemfatura();
        $itemFatura->populate($dados);
        
        $queryFatura = "select * from fatura where id = ".$itemFatura->getIdFatura();
        $queryFatura = $conexao->consultar($queryFatura);
        $resultFatura = mysql_fetch_array($queryFatura);        
       
        $return['sucess'] = false;
        # Se a fatura for saída, deve ser realizada a validacao de estoque
        if($resultFatura['tipo_fatura'] == 'S'){ # Se for saída
            $queryProduto = "select * from  produto where id = ".$itemFatura->getIdProduto();
          
            $queryProduto = $conexao->consultar($queryProduto);
            $retornoP = mysql_fetch_array($queryProduto);
        
            $quantidade = $retornoP['estoque'] - $itemFatura->getQuantidade();
          
            if($quantidade < 0 ){
                $return['sucess'] = false;
                $return['mensagem'] = 'Não há estoque suficiente. A quantidade atual em estoque é: '.$retornoP['estoque'];
            }else{ # Se a saída não deixar o estoque do produto com quantidade negativa
                if($itemFatura->adicionarItemFatura()){
                    # A saída de produto diminui a quantidade do produto em estoque.
                    $this->atualizaEstoque($itemFatura->getIdProduto(), 'S', $itemFatura->getQuantidade());
                    $return['sucess'] = true;
                }
            }
        }else{ # Se for entrada
            if($itemFatura->adicionarItemFatura()){
                # A entrada de produto aumenta a quantidade do produto em estoque.
                $this->atualizaEstoque($itemFatura->getIdProduto(), 'E', $itemFatura->getQuantidade());
                $return['sucess'] = true;
             }
        }
        echo json_encode($return); // - Retorno para o javascript
    }
    
    public function excluirItemFatura($dados){
        $conexao = new Conexao();
        $id = $dados['idItem'];
        
        # Alimentar o objeto com as informações do banco de dados;
        $queryItem = "select * from item_fatura where id = $id";
        $retorno = $conexao->consultar($queryItem);
        $retorno = mysql_fetch_array($retorno);
        $itemFatura = new Itemfatura($retorno);
       
        # Busca a fatura relacionada ao item
        $idFatura = $itemFatura->getIdFatura();
        $queryFatura = "select * from fatura where id = $idFatura";
        $resultFatura = $conexao->consultar($queryFatura);
        $resultFatura = mysql_fetch_array($resultFatura);
        
        # Se a fatura for entrada, deve ser realizada a validacao de estoque
        if($resultFatura['tipo_fatura'] == 'E'){ 
            #busca a quantidade em estoque do produto
            $idProduto = $itemFatura->getIdProduto();
            $queryProduto = "select estoque from produto where id = $idProduto";
            $resultP = $conexao->consultar($queryProduto);
            $resultP = mysql_fetch_array($resultP);
 
            $quantidade = $resultP['estoque'] -  $itemFatura->getQuantidade();          
            if($quantidade < 0 ){
                $return['sucess'] = false;
                $return['mensagem'] = 'Não é possível excluir, pois a quantidade em estoque ficaria negativa.';
            }else{
                if($itemFatura->excluirItemFatura()){
                    $return['sucess'] = true;
                    # Exclusão de um item na entrada, diminui a quantidade do produto em estoque.
                    $this->atualizaEstoque($itemFatura->getIdProduto(), 'S', $itemFatura->getQuantidade());
                }
            }
        }else{
            if($itemFatura->excluirItemFatura()){
                $return['sucess'] = true;
                # Exclusão de um item na saida, aumenta a quantidade do produto em estoque.
                $this->atualizaEstoque($itemFatura->getIdProduto(), 'E', $itemFatura->getQuantidade());
            }
        } 
        echo json_encode($return); // - Retorno para o javascript
    }
    
    private function atualizaEstoque($idProduto, $entradaSaida, $quantidade){
        try{ 
            $conexao = new Conexao();
            $qbProduto = "select * from produto where id = $idProduto";

            $resultP = $conexao->consultar($qbProduto);
            $resultP = mysql_fetch_array($resultP);

            $produto = new Produto($resultP);
            $produto->setEstoque($resultP['estoque']);
            $produto->atualizaEstoque($entradaSaida, $quantidade);

            //$produto->atualizarProduto();  
        }catch(\Exception $e){
            error_log('[ItemFaturaController][atualizaestoque] > '.$e->getMessage());
        }
    }
}