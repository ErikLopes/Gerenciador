<?php
    include("../../../Controller/CategoriaController.php"); 
    include_once("../../../cabecalho.php");
    $categoria = new CategoriaController();

    $idCategoria = $_GET['id'];
    $cat = $categoria->getCategoria($idCategoria);
?>
    <h1><p>Editar categoria</p></h1><br>
        
    <form  action="../../../Controller/CategoriaController.php?acao=editar" name="editarCategoria" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idCategoria" id="idCategoria" value="<?php echo $idCategoria ?>">
        <div class="control-group">
            <label class="control-label">ID</label>
            <input type="text" name="id" id="id" value="<?php echo $idCategoria?>" disabled />
        </div>
        <div class="control-group">
            <label class="control-label">Descrição</label>
            <div class="controls">
                <input type="text" name="descricao" id="descricao"  value="<?php echo $cat['descricao'] ?>" maxlength="50" size="50" required >
            </div>
        </div>
        <div class="control-group">
            <br/>
            <input type="submit" value="salvar" class="btn btn-info" >
        </div>
    </form><br/>
<?php   
    require("../../../rodape.php");
?>
