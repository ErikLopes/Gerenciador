<?php
    include_once(dirname(__DIR__).'/model/Produto.php');
    include_once(dirname(__DIR__).'/model/Conexao.php');

    $acao = $_REQUEST['acao'];

    if($acao != ''){
        $produtoController = new ProdutoController();
        switch($acao){
            case 'cadastrar':
               $produtoController->adicionarProduto($_POST, $_FILES['imagem']); 
            break;
            case 'editar':
                $produtoController->editarProduto($_POST, $_FILES['imagem']);
                break;
            case 'excluir':
                return $produtoController->excluirProduto($_POST);
            case 'cadastrarCategoria':
                return $produtoController->adicionarCategoriaProduto($_POST);
            default :
                header('Location:../../view/module/produto');
        }
    }
  
class ProdutoController{
    
    private $conexao;
    
    function __construct(){
        $this->conexao = new Conexao();
    }
    
    public function index(){
       $query = "select * from produto";
       return $this->conexao->consultar($query);
     
    }
    
    public function adicionarProduto($dados, $imagem){
        
        $produto = new Produto();
        $produto->populate($dados);

        if($imagem && ($imagem['type'] == 'image/jpeg' || $imagem['type'] == 'image/png')){
            $produto->salvarImagem($imagem);
        }
  
        $return = $produto->adicionarProduto(); 
        $idProduto = $return['ultimoId'];
      
        $this->gerenciaCategoriaProduto($idProduto, $dados);
       
        header("Location:../view/module/produto/editProduto.php?id=$idProduto");
    }
    
    public function editarProduto($dados, $imagem){
        $idProduto = (int)$dados['idProduto'];
        
        $produto = new Produto();
        $produto->populate($dados);
        
       /* Identifica se a imagem que esta salva é diferente da que esta sendo passada*/ 
        $param[':idProduto'] = (int)$idProduto;
        $queryProduto = "select * from produto where id = :idProduto";
        $retornoP = $this->conexao->consultarPorCod($queryProduto, $param);
        
       
        if($imagem && $imagem['name']){
            if($retornoP['imagem'] != $imagem['name'])
                $produto->salvarImagem($imagem);
        }else{
            $produto->setImagem( $retornoP['imagem'] ); //Apenas para manter a mesma imagem
        }
                
        $produto->atualizarProduto();
        
        $this->gerenciaCategoriaProduto($idProduto, $dados);
     
        header('Location:../view/module/produto');
    }
    
    public function excluirProduto($dados){
        /* Deve ser validado se o produto esta relacionado a alguma saida */
        $produto = new Produto($dados);
        $produto->populate($dados);
        $id = $produto->getId();
        
        $param[':idProduto'] = $id;
        $query = "select * from item_fatura where id_produto = :idProduto";
        $retornoQuery = $this->conexao->consultarPorCod($query, $param);
        //$retornoQuery = mysql_fetch_array($query);
     
        $return['sucess'] = false;
        if(!$retornoQuery){ // Se não tiver ligação com a fatura
            
            // - Valida ligação com categoria
            $queryP = "select * from categoria_produto where id_produto = :idProduto";
            $retornoQueryP = $this->conexao->consultarPorCod($queryP, $param);
            //$retornoQueryP = mysql_fetch_array($queryP);
            
            if(!$retornoQueryP){ // Se não tem ligação com categoria nem com fatura
                if($produto->excluirProduto()){
                    $return['sucess'] = true;
                }
            }else{ 
                 $return['mensagem'] = 'Não foi possível excluir, pois o mesmo possui ligação com categoria.';
            }
        }else{   
            $return['mensagem'] = 'Não foi possível excluir, pois o produto tem ligação com entrada/saída.';
        }
        
        echo json_encode($return); // - Retorno para o javascript
    }
    
    public function getProduto($id){
        $dados[':id'] = (int)$id;
        $query = "select * from produto where id = :id";
        return $this->conexao->consultarPorCod($query, $dados);
    }
    public function getCategoria($idProduto){
        $dados[':idProduto'] = $idProduto;
        $query = "select * from categoria_produto where id_produto = :idProduto  and pai_filha_sub = 0 ";
        $resultQuery = $this->conexao->consultarPorCod($query, $dados);
      
        $retorno['idPai'] = $resultQuery['id_categoria'];
        
        $query = "select * from categoria_produto where id_produto = :idProduto  and pai_filha_sub = 1";
        $resultQuery = $this->conexao->consultarPorCod($query, $dados);
        
        $retorno['idFilha'] = $resultQuery['id_categoria'];
        
        $query = "select * from categoria_produto where id_produto = :idProduto  and pai_filha_sub = 2";
        $result = $this->conexao->consultarPorCod($query, $dados,1);
      
        $i = 0;   
        foreach($result as $re){
            $retorno['subCat'][$i] =    $re['id_categoria'];
            $i++;
        }
       
        return $retorno;
    }
    
    public function gerenciaCategoriaProduto($idProduto, $dados){
        $param[':idProduto'] = (int)$idProduto;
        $queryDelete = "delete from categoria_produto where id_produto = :idProduto ";
        $queryDelete = $this->conexao->inserirEditarExcluir($queryDelete, $param);
        
       
        if($dados['catPai']){
           $this->salvaCategoriaProduto($idProduto, $dados['catPai'], 0);
        }if($dados['catFilha']){
             $this->salvaCategoriaProduto($idProduto, $dados['catFilha'], 1);
        }
        $subCategorias = explode(",", $dados['subCat']);
        
        foreach($subCategorias as $sub){
            $categorias = explode("chkSub_", $sub);
            
            foreach($categorias as $idCategoria){
                if($idCategoria){
                  $this->salvaCategoriaProduto($idProduto, $idCategoria,2);   
                }
            }
        }
    }
    public function salvaCategoriaProduto($idProduto, $idCategoria, $paiFilhaSub ){
        /*
         * $paiFilhaSub:
         * 0 - pai
         * 1 - filha
         * 2 - sub
         */
        $param[':idProduto']   = (int)$idProduto;
        $param[':idCategoria'] = (int)$idCategoria;
        $param[':paiFilhaSub'] = (int)$paiFilhaSub;
        $query = "insert into categoria_produto (id_produto, id_categoria, pai_filha_sub)values(:idProduto, :idCategoria, :paiFilhaSub)";
        $this->conexao->inserirEditarExcluir($query, $param);
    }
}