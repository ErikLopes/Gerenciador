<?php
    include_once(dirname(__DIR__).'/model/Fatura.php');
    include_once(dirname(__DIR__).'/model/Itemfatura.php');
    include_once(dirname(__DIR__).'/model/Conexao.php');

    $acao = $_REQUEST['acao'];

    if($acao != ''){
        $faturaController = new FaturaController();
        switch($acao){
            case 'cadastrar':
               $faturaController->adicionarFatura($_POST); 
                break;
            case 'editar':
                $faturaController->editarFatura($_POST);
                break;
            case 'excluir':
                $faturaController->excluirFatura($_POST);
                break;  
        }
    }

class FaturaController{
    
    private $conexao;
    
    function __construct(){
        $this->conexao = new Conexao();
    }
    
    public function index($acao){
        
        if($acao == 'entrada')
            $query = "select * from fatura where tipo_fatura = 'E'";
        else
            $query = "select * from fatura where tipo_fatura = 'S'";
        
        return $this->conexao->consultarPorCod($query, null, 1);
   
    }
    
    public function adicionarFatura($dados){
      
        $fatura = new Fatura();
        $fatura->populate($dados);
        $return = $fatura->adicionarFatura();
       
        $idFatura = $return['ultimoId'];
        
        header("Location:../view/module/fatura/editFatura.php?id=$idFatura");
    }
    
    public function editarFatura($dados){
        $fatura = new Fatura(); error_log(print_r($dados, true));
        $fatura->populate($dados);
        $fatura->atualizarFatura();
        $idFatura = $dados['idFatura'];
        
        header("Location:../view/module/fatura/editFatura.php?id=$idFatura");
    }
    
    public function getFatura($id){
        $param['idFatura'] = (int)$id;
        $query = "select * from fatura where id = :idFatura";
        return  $this->conexao->consultarPorCod($query, $param);
        
    }
    public function getProdutosFatura($id){
        $param[':idFatura'] = (int)$id;
        $query = "select  IFA.*, P.descricao  from item_fatura as IFA "
                . "inner join produto as P ON  P.id = IFA.id_produto"
                . "  where id_fatura = :idFatura group by P.id";
        return $this->conexao->consultarPorCod($query, $param);
        
    }
    
    public function excluirFatura($dados){
        $idFatura = (int)$dados['idFatura'];
        $conexao = new Conexao();
        
        $qVerificaItens = "select * from item_fatura where id_fatura = $idFatura";
     
        $qVerificaItens = $conexao->consultar($qVerificaItens);
        $itensFatura = mysql_fetch_array($qVerificaItens);
    
        $return['sucess'] = false;
        if($itensFatura){ # Se houver itens na fatura
              $return['mensagem'] = 'Não é possível excluir, pois há produto vinculados a esta fatura.';
        }else{
            $qbFatura = "select * from fatura where id = $idFatura";
            $qbFatura = $conexao->consultar($qbFatura);
            $resultFatura = mysql_fetch_array($qbFatura);
           
            $fatura = new Fatura($resultFatura);
            
            if($fatura->excluirFatura()){
                $return['sucess'] = true;
            }
        }
        
        echo json_encode($return); 
    }
    
    public function getProdutos($entradaSaida, $idFatura = null){
        $param['idFatura'] = (int)$idFatura;
      
        $queryItensFatura = "Select * from item_fatura where id_fatura = :idFatura";
        $itensFatura = $this->conexao->consultarPorCod($queryItensFatura, $param, 1);
       
        // - Controle para que não seja possivel inserir o mesmo item duas vezes na fatura
        $ids =  0;
        foreach($itensFatura as $itens){
            if(!$ids)
                $ids = $itens['id_produto'];
            else
                $ids .= ','.$itens['id_produto'];
        }
     
        # Se for saída trará apenas produto que possuem estoque...Se for entrada trará todos os produtoa
        if($entradaSaida == 'S')
            $where = "where  id not in($ids)";
        else
            $where = "where estoque > 0 and id not in($ids)";
                
        $query = "select * from produto $where";
        $return = $this->conexao->consultarPorCod($query, null, 1);
        
        return $return;
    }
}