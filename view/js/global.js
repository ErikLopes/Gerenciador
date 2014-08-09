
    function validaImagem(){

        var extensoes = ["jpg", "png"];

        if($('#imagem') && $('#imagem')[0].value != ""){ // se tiver um input de name imagem e tiver valor
            var caminho = '';
            var ext = '';
            caminho = $('#imagem')[0].value; // Aqui identifico o arquivo
            caminho = caminho.split(".") // criei um array quebrando o .

            var ext = caminho.pop() // a varivel vai ficar como por exemplo jpg ou  png
           // - percorre as extensoes permitidas
            for (var i = 0; i <  extensoes.length; i++){
                if(extensoes[i] == ext)
                    return true
            }
            alert('Extensão não permitida.\nA extensão deve ser .jpg ou .png');
            return false;
        }else{
            return true;
        }
    }


    function montaCategoria(){
        $('#modalAdicaoEdicao').modal('show');  
    }
    function buscaCategoriaFilha(controle){
        /*
         * se a variavel controle for zero então busca filha, senão busca as subcategorias.
         */
        var id = "";
        
        $('#checkSubCategoria').empty(); // Limpa a div onde fica os checkbox
        if(!controle || controle == 0){
            $("#categoriaFilha").empty();  // limpa os options da categoria filha
            $("#categoriaFilha").append('<option selected="selected" value="">Escolha uma categoria</option>');  // Adiciona um option vazio           
            id = $('#selectCategoria').val();  // Pega o valor da categoria pai
        }else{
            id = $('#categoriaFilha').val(); // Pela o valor da Categoria Filha 
           // alert(id);
            
            if (id){
                $('#checkSubCategoria').css('display', 'block'); // Mostra div
                
                var categoriaFilhaTemp = $('#categoriaFilha').val();
                var onclickTemp = "onclick="+"document.getElementById('idPaiInserir').value='"+categoriaFilhaTemp+"';document.getElementById('campoAtualizar').value='checkSubCategoria'";
                var novoElemento =  "<a data-toggle='modal' "+onclickTemp+" href='#modalNovaCategoria' class='btn btn-primary'>Nova categoria</a><br/>";
                $("#checkSubCategoria").prepend(novoElemento); // Adiciona um botão para cadastro de nova categoria
                
            }else
                $('#checkSubCategoria').css('display', 'none'); // oculta div onde fica os checkbox
        }
        
        var url = "../../../Controller/CategoriaController.php?acao=busca";
        var dados = "id="+id;

        $.post(url, dados, function(retorno){
            ret =  JSON.parse(retorno);
            
            if(ret){
                if(!controle){
                    for(var i = 0; i < ret.length; i++){ // Adiciona options dentro do select
                        $('#categoriaFilha').append('<option value='+ret[i]['id']+'>'+ret[i]['descricao']+'</option>');
                    }
                }else{ // Cria Checkbox 
                    
                    for(var i = 0; i < ret.length; i++){
                        var check = '';
                        check =  '<input id="chkSub_' + ret[i]['id'] + '" type="checkbox" value="" /> &nbsp;&nbsp;' + ret[i]['descricao']+'<br/>';
            
                        $('#checkSubCategoria').append(check);
                    }
                }
            }
        });
    }
    function novaCategoria(){
        var id = $('#idPaiInserir').val();
        var url =  "../../../Controller/CategoriaController.php?acao=cadastrar";
        var dados = 'idPai='+id+'&javascript=1&descricao='+$('#novaCategoriaModal').val();
        var campoAtualizar = $('#campoAtualizar').val();
      
        $.post(url, dados, function(retorno){
            var ret = JSON.parse(retorno);
            if(campoAtualizar == 'checkSubCategoria'){ // Aqui é para criar os checkbox
                var check = '';
                check =  "<input id='chkSub_'" + ret['id'] + "' type='checkbox' value='' />&nbsp;&nbsp;  " + ret['descricao']+"<br/>";
                $('#checkSubCategoria').append(check);
               
            }else{ // Cria os elementos dentro do select
                $('#'+campoAtualizar).append('<option value='+ret['id']+'>'+ret['descricao']+'</option>');
            
            }
        });
        $('#novaCategoriaModal').val('');
        $('#modalNovaCategoria').hide();
        $('#modalNovaCategoria').modal('toggle')
    }
    
    function abreModalNovaCategoria(){
       $('#modalAdicaoEdicao').modal('toggle')
       $('#novaCategoria').modal('show'); 
    } 
    
    function validaNovaFilha(){
        if(!$('#selectCategoria').val()) // Se não existe categoria pai selecionada
            return false;
        else
            return true;
    }
    function adicionarEditarCategoria(){
        var id = $('#idPaiInserir').val();
        var url =  "../../../Controller/ProdutoController.php?acao=cadastrarCategoria";
        var dados = 'idPai='+id+'&javascript=1&descricao='+$('#novaCategoriaModal').val();
        var campoAtualizar = $('#campoAtualizar').val();
      
        $.post(url, dados, function(retorno){
            var ret = JSON.parse(retorno);
            if(campoAtualizar == 'checkSubCategoria'){ // Aqui é para criar os checkbox
                var check = '';
                check =  "<input id='chkSub_'" + ret['id'] + "' type='checkbox' value='' />&nbsp;&nbsp;  " + ret['descricao']+"<br/>";
                $('#checkSubCategoria').append(check);
               
            }else{ // Cria os elementos dentro do select
                $('#'+campoAtualizar).append('<option value='+ret['id']+'>'+ret['descricao']+'</option>');
            }
    
        });
    }    
    


