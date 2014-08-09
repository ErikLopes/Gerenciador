
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Gerenciador</title>
    
    <link rel="stylesheet" type="text/css"  href="http://localhost/Gerenciador/estilo.css" />
    <link rel="stylesheet" type="text/css"  href="http://localhost/Gerenciador/view/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css"  href="http://localhost/Gerenciador/view/css/bootstrap.css.map"/>
    <script language="javascript" type="text/javascript" src="http://localhost/Gerenciador/view/js/jquery-2.1.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="http://localhost/Gerenciador/view/js/bootstrap.js"></script>
    <script language="javascript" type="text/javascript" src="http://localhost/Gerenciador/view/js/global.js"></script>
</head>
<body style="background: #8DB6CD"> 
    <div class="container navbar-default" >
      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://localhost/Gerenciador">Gerenciador</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="<?php echo "http://localhost/Gerenciador/view/module/produto"?>">Produto</a></li>
              <li><a href="<?php echo 'http://localhost/Gerenciador/view/module/categoria'?>">Categoria</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Fatura <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="<?php echo 'http://localhost/Gerenciador/view/module/fatura/index.php?tipo=entrada'?>">Entrada</a></li>
                  <li><a href="<?php echo 'http://localhost/Gerenciador/view/module/fatura/index.php?tipo=saida' ?>">Saida</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <hr/>