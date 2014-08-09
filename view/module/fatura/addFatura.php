<?php
    include_once("../../../cabecalho.php");
    $tipo = $_GET['tipo'];
    
    if($tipo != 'entrada' && $tipo != 'saida')
        header ("Location:index.php");
?>
    <h1><p>Nova <?php echo ucwords($tipo) ?></p></h1><br>
    <form  action="../../../Controller/FaturaController.php?acao=cadastrar" name="cadastroFatura" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="tipoFatura" id="tipoFatura" value="<?php echo $tipo?>">
        <div class="control-group">
            <label class="control-label" >Data </label>
            <div class="controls">
                <input type="date" name="dataCadastro" id="dataCadastro"  required><br/><br/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Descrição</label>
            <div class="controls">
                <input type="text" name="descricao" id="descricao" maxlength="255" required>
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

    