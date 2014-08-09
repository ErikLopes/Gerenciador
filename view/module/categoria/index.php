<?php
    
    require("../../../Controller/CategoriaController.php"); 
    include_once("../../../cabecalho.php");
    
    $categoriaController = new CategoriaController();
        
    $todasCat = $categoriaController->index();
    $categorias = $categoriaController->busca(0);
    
?>

    <h1><p>Categorias</p></h1><br>
    <!--<input type="button" onClick="document.location='addCategoria.php?idPai=0'" value="Nova categoria" class='btn btn-info'><br/><br/>-->
    <button type="button" onclick="montaCategoria()" class='btn btn-primary'>Cadastro</button><br/><br/>
    <table border="1" class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>ID Pai</th>
            <th colspan="2">ações</th>
        </tr>
        <?php foreach($todasCat as $cat):?>
        <tr>
            <td><?php echo $cat['id']?></td>
            <td><?php echo $cat['descricao']?></td>
            <td><?php echo $cat['id_pai']?></td>
            <td><a href="editCategoria.php?id=<?php echo $cat['id']?>" class="btn btn-info">Editar</a></td>
            <td><button class="btn btn-info" type="button" name="excluir" onclick="excluirCategoria(<?php echo $cat['id']?>, <?php echo $cat['id_pai']?>)">Excluir</button></td>                
        </tr> 
        <?php endforeach; ?>
    </table>

    <!-- Modal da categoria -->
    <div id="modalAdicaoEdicao" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="tituloModal" name="tituloModal" align="center"></h4>
            </div>
            <div class="modal-body">
                <form name="formModal" id="formModal">
                    <input type="hidden" name="categoriaPai" id="categoriaPai" value=""/>
                        <label> Categoria:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <select name="selectCategoria" id="selectCategoria" onchange="buscaCategoriaFilha(0)">
                            <option value="">Escolha uma categoria</option>
                                <?php foreach ($categorias as $categoria){ ?>
                                    <option value="<?php echo $categoria['id']?>"> <?php echo $categoria["descricao"]?></option>
                                <?php }?>
                        </select>
                        <a data-toggle="modal" onclick="$('#idPaiInserir').val(0);$('#campoAtualizar').val('selectCategoria')" href="#modalNovaCategoria" class="btn btn-primary">Nova categoria</a><br/><hr/>
                               
                        <label> Sub categoria:</label>
                        <select name="categoriaFilha" id="categoriaFilha" onchange="buscaCategoriaFilha(1)">
                            <option value="">Escolha uma categoria</option>
                        </select>
                        <a data-toggle="modal" name='lnovaCategoriaFilha' onclick="$('#idPaiInserir').val($('#selectCategoria').val());$('#campoAtualizar').val('categoriaFilha');" href="#modalNovaCategoria" class="btn btn-primary">Nova categoria</a><br/><hr/>
                        
                       <div  id="checkSubCategoria" name="checkSubCategoria" style="display: none">
                            <!-- Aqui será alimentado por javascript-->  
                        </div>
                    </form>
                    <br/>
                    </div>
                    <div class="modal-footer">
                        <div name="dmensagemModal" id="dmensagemModal" style="display: none">
                            <label id="lmensagemModal" name="lmensagemModal"></label>
                        </div>  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Modal Para o cadastro de categoria-->
    <div class="modal" id="modalNovaCategoria" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3 class="modal-title">Nova Categoria</h3>
                </div><div class="container"></div>
                <div class="modal-body">
                    <label>Descrição:</label>
                    <input type="text" id="novaCategoriaModal" name="novaCategoriaModal" size="89">
                </div>
                <div class="modal-footer">
                  <a href="#" data-dismiss="modal" class="btn">Fechar</a>
                  <button type="button" class="btn btn-primary" onclick="novaCategoria()">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="idPaiInserir" id="idPaiInserir" value="0">
    <input type="hidden" name="campoAtualizar" id="campoAtualizar" value="">
<script language="javascript" type="text/javascript">

    function excluirCategoria(id, idPai){
        var dados = 'idCategoria='+id;
        var url   = '../../../Controller/CategoriaController.php?acao=excluir';
        
        if(confirm('Confirma a exclusão da categoria '+id)){
            $.post(url, dados, function(retorno){
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

</script>
<?php   
    require("../../../rodape.php");
?>

    