<?php
    include_once(dirname(__DIR__).'/model/Categoria.php');
    include_once(dirname(__DIR__).'/model/Conexao.php');

    $acao = $_REQUEST['acao'];

    if($acao != ''){
        $categoriaController = new CategoriaController();
        switch($acao){
            case 'cadastrar':
               $categoriaController->adicionarCategoria($_POST); 
                break;
            case 'editar':
                $categoriaController->editarCategoria($_POST);
                break;
            case 'excluir':
                $categoriaController->excluirCategoria($_POST);
                break;  
            case 'busca' :
            	$categoriaController->buscaPorJavaScript($_POST["id"]);
        }
    }

class CategoriaController{
    
    private $conexao;
    
    function __construct(){
        $this->conexao = new Conexao();
    }
    
    public function index(){
        $queryCategoria = "select * from categoria";
        return $this->conexao->consultarPorCod($queryCategoria, null, 1);
    }
    
    public function busca($id){
        $param[':idPai']  = (int)$id;
        $queryCategoria = "select * from categoria where id_pai= :idPai";      
        $return  = $this->conexao->consultarPorCod($queryCategoria, $param, 1);
        
        return $return;
    }
    
    public function adicionarCategoria($dados){
        $categoria = new Categoria($dados);       
        $return = $categoria->adicionarCategoria();
        $ultimoId = $return['ultimoId'];
        
        if($dados['javascript']){ # retorno para JavaScript
            $param[':id'] = $ultimoId;
            $query = "select * from categoria where id = :id ";
            $result = $this->conexao->consultarPorCod($query, $param);
           echo json_encode($result);
             
        }else{ // Caso seja um cadastro por uma tela normal
            header('Location:../view/module/categoria');
        }
    }
    
    public function editarCategoria($dados){
        $categoria = new Categoria($dados);
        $categoria->atualizarCategoria();
        header('Location:../view/module/categoria');
    }
    
    public function excluirCategoria($dados){
        //$conexao = new Conexao();
        $param['idCategoria'] = (int)$dados['idCategoria'];
       
        if($param['idCategoria']){
             #Valida se a categoria possui Filha
            if(!$this->categoriaPossuiFilha($param['idCategoria'])){
                
                // - Valida ligação com produto
                $queryP = "select * from categoria_produto where id_categoria = :idCategoria";
                $retornoQueryP = $this->conexao->consultarPorCod($queryP, $param);
                //$retornoQueryP = mysql_fetch_array($queryP);
                
                if(!$retornoQueryP){
                    $query = "delete from categoria where id = :idCategoria";

                    $return['sucess'] = false;
                    if($this->conexao->inserirEditarExcluir($query, $param)){
                        $return['sucess'] = true;
                    }else{
                        $return['mensagem'] = 'Ocorreu um erro interno ao excluir.';
                    }
                }else{ // Se possuir ligação com produto;
                    $return['mensagem'] = 'Não foi possível excluir, pois o mesmo possui ligação com produtos.';
                }
                
            }else{ # Se possuir filha
                 $return['mensagem'] = 'Não é possivel excluir uma categoria que possui SubCategorias.';
            }
        }else{
            $return['mensagem'] = 'Não foi possível identificar a categoria a ser excluida.';
        }
      
        echo json_encode($return); // - Retorno para o javascript
    }
    
    public function getCategoria($id){
        $param['idCategoria'] = (int)$id;
        
        $query = "select * from categoria where id = :idCategoria";
      
        return $this->conexao->consultarPorCod($query, $param);
    }
    
    protected function categoriaPossuiFilha($id){
        /*
         * Função criada para identificar uma categoria possui filha
         */
        $param['id'] = (int) $id; error_log($id);
        $query = "select * from categoria where id_pai = :id";
        
        $retorno = $this->conexao->consultarPorCod($query, $param);
       
        if($retorno)
            return true;
        else 
            return false;
    }
    public function buscaPorJavaScript($id){
     
        $dados[':cod'] = (int)$id;
    	$queryCategoria = "select * from categoria where id_pai= :cod";
    	echo json_encode($this->conexao->consultarPorCod($queryCategoria, $dados, 1)); // retorno para o JavaScript
    }
}
