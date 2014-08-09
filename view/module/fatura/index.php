<?php
    require("../../../Controller/FaturaController.php"); 
    include_once("../../../cabecalho.php");
    
    $tipo = $_GET['tipo'];
   
    if($tipo != 'entrada' && $tipo != 'saida')
        header ("Location:../../../index.php");
    
    $faturaController = new FaturaController();
    $faturas  = $faturaController->index($tipo);
  
?>
    <h1><p> <?php echo ucwords($tipo) ?></p></h1><br>
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>"/>
    <input type="button" class="btn btn-info" onClick="document.location='addFatura.php?tipo=<?php echo $tipo ?>'" value="Nova"><br/><br>

    <table border="1"  class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Data Cadastro</th>
            <th>Descrição</th>
            <th colspan="2">Ações</th>
        </tr>
        <?php foreach($faturas as $fat):
            $data = new DateTime($fat['data_cadastro']);
        ?>
        <tr>
            <td><?php echo $fat['id']; ?></td>
            <td><?php echo $data->format('d/m/Y'); ?></td>
            <td><?php echo $fat['descricao']; ?></td>
            <td><a href="editFatura.php?id=<?php echo $fat['id']?>" class="btn btn-info">Editar</a></td>
            <td><button type="button" class="btn btn-info" onclick="validaExclusao(<?php echo $fat['id'] ?>)">Excluir</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <script language="JavaScript" type="text/javascript">
        
        function validaExclusao(id){
            var dados = '';
            var inputs = document.getElementsByTagName('input');
            var url = '../../../Controller/FaturaController.php?acao=excluir';
            
            if(id){
                if(confirm('Confirma a exclusão da fatura '+id+ '?')){
                    dados = 'idFatura='+id;
                    $.post(url, dados,function(retorno){
                        var obj = JSON.parse(retorno);
                        
                        if(obj['sucess'] == true || obj['sucess'] == 1){
                            alert('Exclusão realiza com sucesso.');
                            location.href = "index.php?tipo="+$('#tipo').val();
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
        
