var divEmail = document.querySelector('div.email');
var divPasswd = document.querySelector('div.passwd');

const error = new URLSearchParams(location.search);

document.addEventListener('deviceready', function () {

    // Função do Jquery - quando o evento submit é realizado, chama a função SendLoginForm
    $("#login_form").submit(SendLoginForm);

    if ( error.get('error') == 1 ) {
        divEmail.textContent = "Log In expirado, favor logar novamente";
    }
});


function SendLoginForm () {

    // Declaração de variáveis
    var form_value;
    var email, passwd;
    var form_value = ($(this).serializeArray());
    var pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var pattern_passwd = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/;
    // Final da declaração das variáveis


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
                    'Accept': ['application/json','text/javascript']
                }
            });
            var myJSON = JSON.stringify(login_info);
            var url = "http://localhost:8080/login";
            let jxhr = $.post(url, myJSON)
            .done(function (data) {
                localStorage.setItem('id', jxhr.getResponseHeader('id'));
                localStorage.setItem('tkn', data.split(":")[1].split("}")[0].split("\"")[1])
                window.location = "html/home.html";
            })
            .fail ( function (data) {
                
                divEmail.textContent = jxhr.responseText;
                event.preventDefault();
            })
            event.preventDefault();
        }
    }
    else{ // Campos Vazios
        divEmail.textContent = "Os campos devem ser preenchidos";
        event.preventDefault();
    }
    event.preventDefault();

}