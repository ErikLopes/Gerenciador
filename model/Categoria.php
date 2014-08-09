<?php

include_once 'Conexao.php';

class Categoria{
    
    private $id;
    private $descricao;
    private $idPai;
    
    public $conexao;
    
    function __construct($propriedades = null) {
        $this->conexao = new Conexao();
        
        if($propriedades){
            $this->setId($propriedades['idCategoria']);
            $this->setDescricao($propriedades['descricao']);
            $this->setIdPai($propriedades['idPai']);
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getIdPai() {
        return $this->idPai;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDescricao($nome) {
        $this->descricao = $nome;
    }

    public function setIdPai($idPai) {
        $this->idPai = ($idPai == '') ? 0 : $idPai ;
    }
    
    public function adicionarCategoria($query = null, $dados = null){
        
        if(!$query){
            $query  = 'insert into categoria (descricao, id_pai) values(:descricao, :idPai )';
            $dados[':descricao'] = $this->getDescricao();
            $dados[':idPai'] = $this->getIdPai();
         }
       
        $this->conexao->inserirEditarExcluir($query, $dados);
        
        $return['ultimoId'] = $this->conexao->getConexao()->lastInsertId();
        return $return;
    }
    
    public function atualizarCategoria($query = null) {
        if(!$query){
            $query = "update categoria set descricao = :descricao";
            $query .= " where id = :id";
        }
        $param['descricao'] = $this->getDescricao();
        $param['id'] = $this->getId();
         
        return $this->conexao->inserirEditarExcluir($query,$param);
    }

}