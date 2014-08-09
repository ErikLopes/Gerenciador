<?php

    include("../../../Controller/FaturaController.php"); 
    include_once("../../../cabecalho.php");
    $fatura = new \FaturaController();

    $idFatura = $_GET['id'];
    $fat = $fatura->getFatura($idFatura);

    if(!$fat)
        header('Location:../fatura/index.php');

    $tipoFatura = ($fat['tipo_fatura'] == 'E') ? 'entrada' : 'saída'; 

    $produtosFatura = $fatura->getProdutosFatura($idFatura);
    $todosProdutos = $fatura->getProdutos($fat['tipo_fatura'], $fat['id']);
 
    $dataCadastro = new \DateTime($fat['data_cadastro']);
    $dataCadastro = $dataCadastro->format('Y-m-d');
   
?>
    <h2><p>Editar <?php echo $tipoFatura. " ".$idFatura;  ?> </p></h2><br>
            
    <form  action="../../../Controller/FaturaController.php?acao=editar" name="editarFatura" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idFatura" id="idFatura" value="<?php echo $fat['id'] ?>"/>
        <div class="control-group">
            <label class="control-label" >Data </label>
            <div class="controls">
                <input type="date" name="dataCadastro" id="dataCadastro" value="<?php echo $dataCadastro ?>" required><br/><br/>
            </div>
            </div>
        <div class="control-group">
            <label class="control-label">Descrição</label>
            <div class="controls">
                <input type="text" name="descricao" id="descricao" maxlength="255" value="<?php echo $fat['descricao'] ?>"required>
            </div>
        </div>
        <div class="control-group">
            <br/>
            <input type="submit" value="salvar" class="btn btn-info" >
        </div>
    </form>
    <hr/>
    
    <h3><p>Itens da fatura</p></h3><br>
        <button type="button" name="novoProduto" class="btn btn-info" onclick="mostrarModal('novo')">Novo Produto</button><br/><br/>
        <table border="1" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th align="right">Quantidade</th>
                    <th align="right" >Preço</th>
                    <th align="center">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while($prod = mysql_fetch_array($produtosFatura)):?>
                    <tr> 
                        <td><?php echo $prod['id'] ?></td>
                        <td><?php echo $prod['descricao'] ?></td>
                        <td align="right"><?php echo $prod['quantidade'] ?></td>
                        <td align="right"><?php echo $prod['preco'] ?></td>
                        <td><button type="button" name="excluir" class="btn btn-info"  onclick="excluirProduto(<?php echo $prod['id']?>)">Excluir</button></td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
        <div id="modalAdicaoEdicao" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="tituloModal" name="tituloModal" align="center"></h4>
                    </div>
                    <div class="modal-body">
                        <form name="formModal" id="formModal">
                            <input type="hidden" name="idProdutoModal" id="idProdutoModal" value=""/>
                            <table>
                                <tr>
                                    <td align="right"> <label>Produto: &nbsp;&nbsp;  </label></td>
                                    <td> 
                                        <select name="selectProdutoModal" id="selectProdutoModal" required>
                                            <option value="">Escolha um produto</option>
                                            <?php   
                                                 foreach($todosProdutos as $prod){
                                                     echo "<option value=".$prod['id'].">". $prod['descricao']."</option>";
                                                 }
                                            ?>
                                         </select><br/><br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><label>Quantidade:&nbsp;&nbsp; </label></td>
                                    <td><input type="text" name="quantidadeModal" id="quantidadeModal" required><br/><br/></td>
                                </tr>
<!--                                <tr>
                                    <td align="right"><label>Preço&nbsp;&nbsp;</label></td>
                                    <td><input type='text' name='precoModal' id='precoModal' ></td>
                                </tr>-->
                                <br/>
                            </table>
                        </form>
                        <br/>
                    </div>
                    <div class="modal-footer">
                        <div name="dmensagemModal" id="dmensagemModal" style="display: none">
                            <label id="lmensagemModal" name="lmensagemModal"></label>
                        </div>  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="adicionarEditarProduto()" id="salvarModal" name="salvarModal">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
<script language="javascript" type="text/javascript">
    
    function mostrarModal(acao, idProduto){
        $('#lmensagemModal').html("");
        if(acao == 'novo'){
            $('#tituloModal').html('<label>Adicionar produto</label>');
            $('#idProdutoModal').val('');
        }
        else{
            $('#tituloModal').html('<label>Edição do produto</label>');
            $('#idProdutoModal').val(idProduto);
        }
        $("#modalAdicaoEdicao").modal('show');
    }
    
    function excluirProduto(idItem){
        var dados = 'idItem='+idItem;
        var url   = '../../../Controller/ItemFaturaController.php?acao=excluir';
        
        if(confirm('Confirma a exclusão do produto '+idItem)){
            $.post(url, dados, function(retorno){
                  var obj = JSON.parse(retorno);
                   if(obj["sucess"] == true || obj['sucess'] == 1){
                        alert('Exclusão realiza com sucesso.');
                        location.href = "editFatura.php?id="+$('#idFatura').val();
                    }else{
                        if(obj['mensagem'])
                            alert(obj['mensagem']);
                        else
                            alert('Não possível realizar a operação devido a um erro interno..');
                    }
            });
        }
    }
   
    function adicionarEditarProduto(){
        var idFatura = $('#idFatura').val();
        var idProduto = $('#selectProdutoModal').val();
        var quantidade = $('#quantidadeModal').val();
        var preco  = $('#precoModal').val();
        var url ="../../../Controller/ItemFaturaController.php?acao=cadastrar";
        var dados = 'idFatura='+idFatura+'&idProduto='+idProduto+'&quantidade='+quantidade+'&preco='+preco;
        
        var mensagem = '';
        
        if(!idProduto)
            mensagem = 'Escolha um produto.';
        if(!quantidade)
            mensagem += '\nDigite a quantidade.';
        
        if(mensagem){
            alert(mensagem);
            return false;
        }else{
            desabilitaCamposModal();

            $("#dmensagemModal").css("display", "block");
            $('#lmensagemModal').html('Por favor, Aguarde...');

            $.post(url, dados, function(retorno){
                var obj = JSON.parse(retorno);
                if(obj['sucess'] == true || obj['sucess'] == 1){
                    $('#lmensagemModal').html("<font color='blue'>Registro salvo com sucesso!</font>");
                    setTimeout(function(){
                        location.href = "editFatura.php?id="+$('#idFatura').val();
                    },1000);
                }else{
                    if(obj['mensagem'])
                        $('#lmensagemModal').html("<font color='red'>"+obj['mensagem']+"</font");
                    else
                        $('#lmensagemModal').html("<font color='red'>Ocorreu um erro. Por favor, tente novamente mais tarde.!");
                }
                habilitaCamposModal();
                
             });
        }
        habilitaCamposModal();
    }
    function desabilitaCamposModal(){
        $('#selectProdutoModal').attr('disabled', true);
        $('#quantidadeModal').attr('disabled', true);
        $('#precoModal').attr('disabled', true);
        $('#salvarModal').attr('disabled', true);
    }
    
    function habilitaCamposModal(){
        $('#selectProdutoModal').attr('disabled', false);
        $('#quantidadeModal').attr('disabled', false);
        $('#precoModal').attr('disabled', false);
        $('#salvarModal').attr('disabled', false);
    }
</script>
<?php   
    require("../../../rodape.php");
?>
     