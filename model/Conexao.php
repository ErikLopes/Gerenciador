<?php

class Conexao{
    
    private  $host = 'localhost';
    private  $usuario = 'root';
    private  $senha = '123456';
    private  $banco = 'gerenciador';
    private $conexao;
    
    
    function __construct() {
        # Como já deixei os variaveis com os devidos valores atribuidos, então também já conecto ao banco
        $this->conectar();
    }
    
    function  conectar(){
        //$this->conexao = mysql_connect($this->host, $this->usuario, $this->senha  );// or die(error_log(mysql_error()));
        //mysql_select_db($this->banco, $this->conexao);
        try{
            if(!isset($this->conexao)){
                $this->conexao = 
                        new PDO('mysql:host='.$this->host.';dbname='.$this->banco.'', $this->usuario, $this->senha,
                                        array(
                                            PDO::ATTR_PERSISTENT => true,
                                        ));
            }
        }catch(PDOException $error){
            error_log('[Conexao][conectar]-> '.$error->getMessage());
        }
    }
    
    function getConexao(){
        return $this->conexao;
    }
    
    function consultar($query){
        $busca = $this->conexao->query($query);
        $busca->execute();      
        return $busca->fetchAll();
    }
    
    function consultarPorCod($query, $dados = null, $all = null){
        $stmt = $this->conexao->prepare($query);
        
        /* Alimenta os campos da busca */
        if($dados){
            foreach($dados as $key => $value){
                if($key)
                    $stmt->bindParam($key, $value);
            }
        }
        
        $stmt->execute();      
        if($all)
            return $stmt->fetchAll();
        else
            return $stmt->fetch();
    }
    
    function inserirEditarExcluir1($query){
        mysql_query($query, $this->conexao);
        $result = mysql_affected_rows();
      
        return $result;
        
    }
    function inserirEditarExcluir($query, $dados = null){
        try{
            $stmt = $this->conexao->prepare($query);

            if($dados){
                foreach($dados as $key => $value){
                    $stmt->bindValue($key, $value);
                }
            }
           
            return $stmt->execute();
            
        }catch(PDOException $erro){
            error_log('[Conexao][InserireditarExcluir]> '.$erro->getMessage());
        }
    }
}
