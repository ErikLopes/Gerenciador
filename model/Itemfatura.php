<?php

include_once('Conexao.php');

class Itemfatura{
    
    private $id;
    private $idFatura;
    private $idProduto;
    private $quantidade;
    private $preco;
    
    public $conexao;
     
    function __construct($propriedades = null) {
        $this->conexao = new Conexao();
        
        if($propriedades){
            $this->setId($propriedades['id']);
            $this->setIdFatura($propriedades['id_fatura']);
            $this->setIdProduto($propriedades['id_produto']);
            $this->setPreco($propriedades['preco']);
            $this->setQuantidade($propriedades['quantidade']);
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($valor) {
        $this->id = $valor;
    }

    public function setIdFatura($valor){
        $this->idFatura = $valor;
    }
    
    public function getIdFatura(){
        return $this->idFatura;
    }
    
    public function setIdProduto($valor) {
        return $this->idProduto = $valor;
    }
    
    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setQuantidade($valor) {
        $this->quantidade = (int)$valor;
    }
    
    public function getQuantidade() {
        return $this->quantidade;
    }
    public function setPreco($valor) {
        $this->preco = (double)$valor;
    }
    
    public function getPreco() {
        return $this->preco;
    }
    
    public function selecionar($query = null) {
        return $this->conexao->consultar($query);
    }
    
     public function populate($array) {
        
        $this->setIdFatura($array['idFatura']);
        $this->setIdProduto($array['idProduto']);
        $this->setPreco($array['preco']);
        $this->setQuantidade($array['quantidade']) ;
      
    }
    
    public function adicionarItemFatura($query = null){
 
        if(!$query){
            $query  = "insert into item_fatura (id_fatura, id_produto, quantidade, preco)".
            $query .= "values('".$this->getIdFatura()."','".$this->getIdProduto()."', '".$this->getQuantidade()."', '".$this->getPreco()."' )";
        }
        
        return $this->conexao->inserirEditarExcluir($query);
    }
    
    public function excluirItemFatura($query = null) {
        if(!$query)
            $query = "delete from item_fatura where id = $this->id";
        
        return $this->conexao->inserirEditarExcluir($query);
    }
    
}