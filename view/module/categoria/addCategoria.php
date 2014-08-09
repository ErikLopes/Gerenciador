<?php
    header("Location:index.php");
    include_once("../../../cabecalho.php");
    $idPai = $_GET['idPai'];
?>
    <h1><p>Nova categoria</p></h1><br>

    <form  action="../../../Controller/CategoriaController.php?acao=cadastrar" name="cadastroCategoria" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idPai" id="idPai" value="<?php echo $idPai?>">
        <div class="control-group">
          <label class="control-label">Descrição</label>
          <div class="controls">
            <input type="text" name="descricao" id="descricao"  maxlength="50" size="50" required >
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

       
    