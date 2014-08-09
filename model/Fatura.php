<?php

include_once('Conexao.php');

class Fatura {

    private $id;
    private $tipoFatura;
    private $dataCadastro;
    private $descricao;

    private $conexao;
    
    function __construct($propriedades = null) {
        $this->conexao = new Conexao();
        
        if($propriedades){
            $this->setId($propriedades['id']);
            $this->dataCadastro = $propriedades['data_cadastro'];
            $this->setDescricao($propriedades['descricao']);
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($valor) {
        $this->id = $valor;
    }
    
    public function getTipoFatura() {
        return $this->tipoFatura;
    }

    public function setTipoFatura($valor) {
        $this->tipoFatura = strtoupper(substr($valor,0,1));
    }

    public function setDataCadastro($valor){
        $this->dataCadastro = $valor;
    }
    
    public function getDataCadastro(){
        return $this->dataCadastro;
    }
    
    public function setDescricao($valor) {
        return $this->descricao = $valor;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function populate($array) {
        $this->setTipoFatura($array['tipoFatura']);
        $this->setDataCadastro($array['dataCadastro']);
        $this->setDescricao($array['descricao']);
        $this->setId($array['idFatura']);
    }
    
    public function selecionar($query = null) {
        return $this->conexao->consultar($query);
    }
    
    public function adicionarFatura($query = null){
 
        if(!$query){
            $query  = "insert into fatura (tipo_fatura, data_cadastro, descricao)".
            $query .= "values(:tipoFatura, :dataCadastro, :descricao )";
        }
        
        $param[':tipoFatura'] = $this->getTipoFatura();
        $param[':dataCadastro'] = $this->getDataCadastro();
        $param[':descricao']    = $this->getDescricao();
        
        $return['sucess'] = $this->conexao->inserirEditarExcluir($query, $param);
        $return['ultimoId'] =  $this->conexao->getConexao()->lastInsertId();
        return $return;
        
    }
    
    public function atualizarFatura($query = null) {
        if(!$query){
            $query = "update fatura set descricao = '".$this->descricao."'"
                    .", data_cadastro = '".$this->getDataCadastro()."' where id = '".$this->getId()."'";
     
        }
         $this->conexao->inserirEditarExcluir($query);
    }
    
    public function excluirFatura($query = null){
            
        if(!$query)
            $query = "delete from fatura where id = '".$this->getId()."'";
     
        return $this->conexao->inserirEditarExcluir($query);
    }
   
}
