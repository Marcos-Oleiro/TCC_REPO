var divEmail = document.querySelector('div.email');
var divPasswd = document.querySelector('div.passwd');



document.addEventListener('deviceready', function () {

    // Função do Jquery - quando o evento submit é realizado, chama a função SendLoginForm
    $("#login_form").submit(SendLoginForm);
});


function SendLoginForm () {

    // Declaração de variáveis
    var form_value;
    var email, passwd;
    var form_value = ($(this).serializeArray());
    var pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var pattern_passwd = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/;
    // Final da declaração das variáveis

    // console.log(form_value.length);
    // return 0;
    var empty_fields = checkEmptyFormFields(form_value);
    divEmail.textContent = "";
    divPasswd.textContent = "";
    
    // Se os campos tiverem preenchidos, deve entrar nesse IF
    if (!empty_fields) {

        email = form_value[0].value.trim();
        passwd = form_value[1].value.trim();

        // se o campo e-mail não é válido, entra nesse IF
        if ( !verifyField(pattern_email,email)) {
            // console.log("E-mail não é válido");
            divEmail.textContent = "O e-mail não é válido";
            event.preventDefault();
            
        }
        // se o campo senha não é válido, entra nesse IF
        else if ( !verifyField(pattern_passwd, passwd)){
            // console.log("Senha não válida");
            divPasswd.textContent = "A senha informada não é valida";
            event.preventDefault();
            
        }
        // Entra aqui se os dois campos estiverem preenchidos corretamente.
        else{
            // console.log("Campos OK");
            var login_info = {
                'email' : email,
                'passwd' : passwd
            };
            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    // 'Accept': ['application/json','text/javascript']
                    'Accept': 'text/plain; charset=utf-8'
                }
            });
            var myJSON = JSON.stringify(login_info);
            var url = "http://localhost:8080/login";
            $.post(url, myJSON,function(data,xhr){
                
                // teste do http code
                httpCode = xhr.status;
                if ( httpCode == 200 ) {  
                    sessionStorage.setItem('id', xhr.getResponseHeader('id'));
                    sessionStorage.setItem('tkn',data.split(":")[1].split("}")[0].split("\"")[1])
                    window.location = "html/home.html";
                }else{
                    divEmail.textContent = "Os campos devem ser preenchidos corretamente.";
                    event.preventDefault();
                }
            });       
            event.preventDefault();
        }
    }
    else{ // Campos Vazios
        // console.log("Campos Vazios");
        divEmail.textContent = "Os campos devem ser preenchidos";
        event.preventDefault();
    }
    event.preventDefault();

}