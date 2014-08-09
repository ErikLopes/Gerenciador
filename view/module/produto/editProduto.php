<?php
    include("../../../Controller/ProdutoController.php"); 
    require("../../../Controller/CategoriaController.php"); 
    include_once("../../../cabecalho.php");
    $produto = new \ProdutoController();

    $idProduto = $_GET['id'];
    $prod = $produto->getProduto($idProduto); //exit();
    
    $categProduto = $produto->getCategoria($idProduto);
   
    
    if($prod['imagem']){
        $caminhoImagem = "../../img/produto/".$prod['imagem'];
    }else{
        $caminhoImagem = "../../img/produto/default.jpg";
    }
     
    $categoriaController = new CategoriaController();
    $categorias = $categoriaController->busca(0);
    
    $dataPublicacao = new \DateTime($prod['data_publicacao']);
    $dataP = $dataPublicacao->format('Y-m-d');
    $dataH = $dataPublicacao->format('H:i');
  
?>  
    <h1><p>Editar produto <?php echo $idProduto?></p></h1><br>
   <button type="button" onclick="montaCategoria()" class='btn btn-primary'>Categorias</button><br/><br/>
    <form  onsubmit="return validaSumit()" action="../../../Controller/ProdutoController.php?acao=editar" name="editarProduto" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idProduto" id="idProduto" value="<?php echo $idProduto ?>">
         <input type="hidden" name='catPai' id='catPai' value=''/>
        <input type='hidden' name='catFilha' id='catFilha' value=''/>
        <input type='hidden' name='subCat' id='subCat' value=''/>
        <div class="control-group">
          <div class="controls">
                <div class="controls">
           <label class="control-label" >Imagem</label>
          </div>
              <img src="<?php echo $caminhoImagem?>" height="100px" widht="100px" />
          </div>
        </div><br/>
         
        
        <div class="control-group">
          
          <div class="controls">
             <input type="file" name="imagem" id="imagem" value="<?php echo $prod['imagem']  ?>" class="btn btn-info"> 
          </div>
        </div>  
        <div class="control-group">
          <label class="control-label">Descrição</label>
          <div class="controls">
            <input type="text" name="descricao" id="descricao" value="<?php echo $prod['descricao']?>" required  />
          </div>
        </div>
         <div class="control-group">
          <label class="control-label">Data Publicação</label>
          <div class="controls">
              <input type="date" name="dataPublicacao" id="dataPublicacao" value="<?php echo $dataP?>" required >
          
              <input type="time" name="horaPublicacao" id="horaPublicacao" value="<?php echo $dataH?>"  required >
          </div>
        </div>
        
        <div class="control-group">
            <br/>
            <input type="submit" value="salvar" class="btn btn-info" >
        </div>
    </form><br/>
    
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
<script>
    $(document).ready(function() {
       $('[data-toggle="modal"]').click(function(e) {
            
            e.preventDefault();
            if(e.target.name == 'lnovaCategoriaFilha' && !validaNovaFilha()){
                alert('Por favor, escolha uma categoria pai!');
                e.stopPropagation();
            }
        });
    }); 
    
    $(window).load(function(){
        povoaCategoria();
    });
    
    function validaSumit(){
        
        if($('#selectCategoria').val() == ''){
            alert('Por favor, escolha pelo menos uma categoria.');
            $('#modalAdicaoEdicao').modal('show'); 
            
            return false;
            
        }else{
            // pego os ids das categorias existentes...
            var catPai = $('#selectCategoria').val();
            var catFilha = $('#categoriaFilha').val();
            var selectedSubs = [];
            var categorias = [];
            
            // - Pega todas as sub categorias checkadas...
            if(($('#checkSubCategoria'))){
                $('#checkSubCategoria input:checked').each(function() {
                    selectedSubs.push($(this).attr('id'));
                });
            }
        
            $('#catPai').val($('#selectCategoria').val());
            $('#catFilha').val($('#categoriaFilha').val());
            $('#subCat').val(selectedSubs);
         
            return true;
        }
        return false;
    }
    
    function povoaCategoria(){
    
        var idPai = '<?php echo $categProduto['idPai']?>';
        var idFilha = '<?php echo $categProduto['idFilha']?>';
        var idSubs  = '<?php echo json_encode($categProduto['subCat'])?> ';
      
        if(idPai)
            $('#selectCategoria').val(idPai);
        else
            $('#selectCategoria').val(0);
        
        $('#selectCategoria').change();
        
        setTimeout(function(){
            if(idFilha){
                $('#categoriaFilha').val(idFilha);
                $('#categoriaFilha').change();
            }
        },1500);
        
        setTimeout(function(){
            
            if(idSubs && idSubs != 'null' && idSubs ){
                    var ids = JSON.parse(idSubs);
                //     console.log(ids);
                if (ids){
                    for(var i = 0; i < ids.length; i++ ){
                        if ($('#chkSub_'+ids[i]))
                            $('#chkSub_'+ids[i]).prop('checked', true);
                    }    
                }
            }
        },2000);
        
    
    }
  
</script>
    
<?php   
    require("../../../rodape.php");
?>
