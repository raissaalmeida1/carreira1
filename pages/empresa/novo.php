<div class="container">
  
    <form method="POST" id="formulario"> <!--02 Alterar o método da requisição para POST -->
        <div class="mb-3 input-group has-validation"> <!-- 06 - incluir a classe de campo com validação has-validation!-->
          <span class="input-group-text">Nome</span>
          <!--Incluir Atributo "name" nos inputs que vão ser enviados para salvar no banco de dados -->        
          <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome da empresa">
          <!--05 informe uma mensagem de validação do campo nome -->
          <div class="invalid-feedback">
            Por favor digite o nome da empresa
          </div>
          <!--05 FIM informe uma mensagem de validação do campo nome -->

        </div>

         
        <!--01-   TELEFONE da EMPRESA -->        
        <div class="mb-3 input-group has-validation "> <!-- 04 - incluir a classe de campo com validação has-validation!-->
          <span class="input-group-text">Telefone</span>
          <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="Digite o telefone da empresa">
          <!--03 informe uma mensagem de validação do campo telefone -->
          <div class="invalid-feedback">
            Por favor digite um telefone
          </div>
          <!--03 FIM informe uma mensagem de validação do campo telefone -->
        </div>
        <!--01-FIM TELEFONE da EMPRESA -->        


        
        <div class="mb-3 input-group">
        <span class="input-group-text">Estado</span>
        <select class="form-select" aria-label="Default select example" id="estados"
           onchange="carregarCidadesIBGE()">
            <option selected>Selecione um estado</option>
        </select>
            <div class="invalid-feedback">
                Por favor selecione um estado
            </div>
        </div>            

        <div class="mb-3 input-group">
        <span class="input-group-text">Cidade</span>
        <select class="form-select" aria-label="Default select example" id="cidade" name="cidade">
            <option selected>Selecione antes um estado</option>
        </select>
          <div class="invalid-feedback">
            Por favor selecione uma cidade
          </div>        
        </div>            

        <div class="mb-3 input-group">
            <input type="hidden" id="geolocalizacao" name="geolocalizacao">
            <span class="input-group-text">Endereço</span>
            <input type="text" class="form-control" id="endereco" placeholder="Digite o endereço da empresa">
            <button type="button"  onclick="obterCoordenadasGoogleMaps()" class="input-group-text">Localizar</button>
            <div class="invalid-feedback">
               Por favor localize a empresa no mapa.
            </div>            
        </div>
        <?php require 'pages/empresa/mapa.php'; ?>

        <!--botão para salvar o formulário -->        
        <div class="mb-3 input-group  justify-content-end ">
           <button type="submit"  class="input-group-text ">Salvar</button>
        </div>            
        
    </form>    
</div>
<script>
     function carregarEstadosIBGE(){
        let url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
        fetch(url)
            .then(response => response.json())
            .then(data => {
                    if (data!=null && data.length>0) {
                        /**ordena o vetor a partir do nome do estado */
                        let ordenado = data.sort( (a,b)=>{  return a.nome.localeCompare(b.nome)   }   );
                        
                        var selectEstado = document.getElementById("estados");
                        /**Utiliza o vetor ordenado para gerar as opções do select */
                        ordenado.forEach(element => {
                            
                            let option = document.createElement('option');
                            option.value= element['sigla'];
                            option.innerText = element['nome'];
                            selectEstado.appendChild(option);

                        });


                    }
            }).catch(error => {
                    console.log("Erro carregando estados "+error);
                });;
     } 


     carregarEstadosIBGE();


     function carregarCidadesIBGE(){
        var selectEstado = document.getElementById("estados");
        var estado = selectEstado.value;
        let url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/'+estado+'/municipios';
        fetch(url)
            .then(response => response.json())
            .then(data => {
                    if (data!=null && data.length>0) {
                        var selectCidade = document.getElementById("cidade");

                        
                        selectCidade.innerHTML = "";
                        
                        data.forEach(element => {
                            let option = document.createElement('option');
                            option.value= element['id'];
                            option.innerText = element['nome'];
                            selectCidade.appendChild(option);
                            
                        });
                    }
            }).catch(error => {
                    console.log("Erro carregando cidades "+error);
            });;
        
     }


     function  validacao(e){
        let passou = true; // flag para saber se passou por toda da validação
        let enome =document.getElementById("nome");
        if (enome.value==null || enome.value == "") {
            passou = false;
            enome.classList.add("is-invalid");
        } else {
            enome.classList.remove("is-invalid");
        }

        let etelefone =document.getElementById("telefone");
        if (etelefone.value==null || etelefone.value.length <9) {
            passou = false;
            etelefone.classList.add("is-invalid");
        } else {
            etelefone.classList.remove("is-invalid");
        }

        let ecidade =document.getElementById("cidade");
        if (ecidade.value==null || ecidade.value=="") {
            passou = false;
            ecidade.classList.add("is-invalid");
        } else {
            ecidade.classList.remove("is-invalid");
        }

        let egeo =document.getElementById("geolocalizacao");
        let eend =document.getElementById("endereco");
        if (egeo.value==null || egeo.value.length <=3) {
            passou = false;
            eend.classList.add("is-invalid");
        } else {
            eend.classList.remove("is-invalid");
        }
        
        if (!passou) {
           e.preventDefault();
        }
        
     }

     document.getElementById("formulario").addEventListener('submit',validacao);

</script>    