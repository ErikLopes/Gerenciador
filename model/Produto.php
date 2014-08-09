<?php
    include_once('Conexao.php');
    
class Produto {

    private $id;
    private $dataCadastro;
    private $descricao;
    private $preco;
    private $estoque;
    private $imagem;
    private $dataPublicacao;

    private $conexao;
    
    function __construct($propriedades = null) {
        $this->conexao = new Conexao();
        
        if($propriedades){
            
            $this->setId($propriedades['id']);
            //$this->dataCadastro = $propriedades['data_cadastro'];
            $this->setDescricao($propriedades['descricao']);
            $this->setEstoque($propriedades['estoque']);
            $this->setPreco($propriedades['preco']);
           
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($valor) {
        $this->id = $valor;
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

    public function setPreco($valor) {
        $this->preco = (double)$valor;
    }
    
    public function getPreco() {
        return $this->preco;
    }
    
    public function setEstoque($valor) {
        $this->estoque = (int)$valor;
    }
    
    public function getEstoque() {
        return $this->estoque;
    }
    
    public function setImagem($valor) {
        $this->imagem = $valor;
    }
    
    public function getImagem() {
        return $this->imagem;
    }
    public function setDataPublicacao($data = null, $hora = null) {
       $dataP = new \DateTime($data.$hora);
       $DataPublicacao = $dataP->format('Y-m-d H:i');
       
       $this->dataPublicacao = $DataPublicacao;
  
    }
    
    public function getDataPublicacao() {
        return $this->dataPublicacao;
    }

    public function populate($array) {
       
        if($array){
            $this->setId($array['idProduto']);
            $this->setDescricao($array['descricao']);
            $this->setPreco($array['preco']) ;
            $this->setDataPublicacao($array['dataPublicacao'],$array['horaPublicacao']);
        }
    }

    public function atualizaEstoque($operacao, $quantidade){
        if($operacao == 'E'){
            $this->setEstoque($this->getEstoque() + $quantidade );
        }else{
            $this->setEstoque($this->getEstoque() - $quantidade );
        }
        
        $query = "update produto set estoque = ".$this->getEstoque()." where id = '".$this->getId()."'";
        
        $this->conexao->inserirEditarExcluir($query);
    }

    public function adicionarProduto($query = null){
 
        if(!$query){
            $dateTime = new DateTime();
          
            $query  = "insert into produto (data_cadastro, descricao, imagem, data_publicacao)".
            $query .= "values(:dataCadastro, :descricao, :imagem, :dataPublicacao)";
            
            $param[':dataCadastro'] = $dateTime->format('Y-m-d H:i:s');
            $param[':descricao']    = $this->getDescricao();
            $param[':imagem']       = $this->getImagem();
            $param[':dataPublicacao'] = $this->getDataPublicacao();
             
        }
      
        $this->conexao->inserirEditarExcluir($query, $param);
        $return['ultimoId'] = $this->conexao->getConexao()->lastInsertId();
        return $return;
    }

    public function selecionar($query = null) {
        return $this->conexao->consultarPorCod($query);
    }
    
    public function atualizarProduto($query = null, $dados = null) {
        if(!$query){
            
            $query = "update produto set descricao = :descricao ,imagem = :imagem"
                    .", data_publicacao = :dataPublicacao where id = :id";
            
            $dados[':descricao'] = $this->getDescricao();
            $dados[':imagem'] = $this->getImagem();
            $dados[':dataPublicacao'] = $this->getDataPublicacao();
            $dados[':id'] = $this->getId();
        }
       /// error_log($query); exit();
        $this->conexao->inserirEditarExcluir($query, $dados);
    }

    public function excluirProduto($query = null) {
        
        if(!$query)
            $query = "delete from produto where id = :idProduto";
        
        $param[':idProduto'] = $this->getId();
        return $this->conexao->inserirEditarExcluir($query, $param);
    }
    
    public function salvarImagem($foto){
        /*
        $ext =   explode('.', $foto["name"]);
        $nomeImagem = md5(uniqid(time())).".".array_pop($ext);
        */
        $caminhoImagem =  dirname(__DIR__).'/view/img/produto/'.$foto["name"];
        move_uploaded_file($foto["tmp_name"], $caminhoImagem);
        $this->setImagem($foto["name"]);
    }
}
