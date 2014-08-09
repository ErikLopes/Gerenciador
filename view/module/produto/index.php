<?php
    require("../../../Controller/ProdutoController.php"); 
    include_once("../../../cabecalho.php");
    
    $produtoController = new ProdutoController();
    $produtos = $produtoController->index();
    
  // error_log(print_r($produtos, true));
   //  exit();
?>
    <h1><p>Produtos</p></h1><br/>

    <input type="button" class="btn btn-info" onClick="document.location='addProduto.php'" value="Novo produto"><br/><br/>

    <table border="1"  class="table table-striped">
        <tr>
            <th align="center">ID</th>
            <th>Data Cadastro</th>
            <th>Descrição</th>
            <th align="right">Estoque</th>
            <th colspan="2">Ações</th>
        </tr>
        <?php foreach($produtos as $prod):
           $data = new DateTime($prod['data_cadastro']);
        ?>
        <tr>
            <td align="center"><?php echo $prod['id']; ?></td>
            <td><?php echo $data->format('d/m/Y'); ?></td>
            <td><?php echo $prod['descricao']; ?></td>
            <td align="right"><?php echo $prod['estoque']; ?></td>
            <td><a href="editProduto.php?id=<?php echo $prod['id']?>" class="btn btn-info">Editar</a></td>
            <td><button type="button" class="btn btn-info" onclick="validaExclusao(<?php echo $prod['id'] ?>)">Excluir</button></td>
        </tr>

        <?php endforeach; ?>
    </table>
    <script language="JavaScript">
        
        function validaExclusao(id){
            var dados = '';
            var url = '../../../Controller/ProdutoController.php?acao=excluir';
       
            if(id){
                if(confirm('Confirma a exclusão do produto '+id+ '?')){
                    dados = 'idProduto='+id;
                  
                    $.post(url, dados,function(retorno){
                       
                        var obj = JSON.parse(retorno);
                     
                        if(obj["sucess"] == true || obj['sucess'] == 1){
                            alert('Exclusão realiza com sucesso.');
                            location.href = "index.php";
                        }else{
                            
                            if(obj['mensagem'])
                                alert(obj['mensagem']);
                            else
                                alert('Não possível realizar a operação devido a um erro interno..');
                        }
                    });
                }
            }
        }
        
    </script>    
<?php   
    require("../../../rodape.php");
?>

