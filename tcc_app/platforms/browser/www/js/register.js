var divPass = document.querySelector('div.pass');
var divMail = document.querySelector('div.mail');
var divFields = document.querySelector('div.fields');


/* Validação dos campos do formulário
    Campo nickname -> mínimo 4 letras, máximo 10, pode ter maiúsculas, minúsculas e simbolos (-_$);
    E-mail -> Regex
    Senha -> mínimo 6 caracteres, máximo 10 (tem que conter 1 letra maiúscula, 1 número, um caracter special (#?!@$%^&*-), 1 letra minúscula)

*/

document.addEventListener('deviceready', function () {

    // Função do Jquery - quando o evento submit é realizado, chama a função SendFormData
    $("#register_form").submit(SendFormData);
});

// função para tratar as informações que vem no formulário
function SendFormData() {
    // Variáveis - Começo
    var form_value;
    var nickname, email, senha;
    var form_value = ($(this).serializeArray());
    var empty_fields = checkEmptyFormFields(form_value);

    var pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var pattern_passwd = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/;
    var pattern_nickaname = /([A-Z]*|[a-z]*|[0-9]*|[\-_\$]*)^.{4,10}/;
    // Variáveis - Fim

    divFields.textContent = "";
    divMail.textContent = "";
    divPass.textContent = "";
    // console.log(empty_fields);
    if (!empty_fields) {

        // console.log('Campos ok!');
        // var teste = checkEqualsPasswds( form_value[2].value.trim() , form_value[3].value.trim() );
        // console.log(teste);

        if (checkEqualsPasswds(form_value[2].value.trim(), form_value[3].value.trim())) {
            nickname = form_value[0].value.trim();
            email = form_value[1].value.trim();
            passwd = form_value[2].value.trim();

            // verifica de o campo nickname é válido
            if (!verifyField(pattern_nickaname, nickname)) {
                divFields.textContent = "O nome de usuário deve corresponder ao mínimo exigido";
                event.preventDefault();
            }
            // verifica de o campo email é válido
            else if (!verifyField(pattern_email, email)) {
                divMail.textContent = "O email deve ser válido";
                event.preventDefault();
            }
            // verifica de o campo password é válido
            else if (!verifyField(pattern_passwd, passwd)) {
                divPass.textContent = "A senha deve correspondr ao mínimo exigido";
                event.preventDefault();
            }
            // todos os campos passam no teste e os dados do formuládevem devem ir pro beck-end
            else {
                // console.log('Email válido');
                var user_info = {
                    'nickname' : nickname,
                    'email' : email,
                    'passwd' : passwd
                }
                var myJSON = JSON.stringify(user_info);
                // console.log(myJSON);
                // window.location = "https://www.google.com" // testando o redirecionamento se os campos tiverem ok
                var url = "http://localhost:8080/users";
                $.post(url, myJSON,function(data){console.log(data);});

                event.preventDefault();
            }
        }
        else {
            // console.log("As senhas devem ser iguais!")
            divPass.textContent = "As senhas devem ser iguais!";
            event.preventDefault();
        }
    }
    else {
        divFields.textContent = "Todos os campos devem ser preenchidos."
        event.preventDefault();
        // console.log("campos vazios");
    }
}


// verifica se há algum campo no formulário vazio, retorna true se houve campo vazio
function checkEmptyFormFields(form_value) {

    if ((form_value[0].value.trim().length == 0) || (form_value[1].value.trim().length == 0) || (form_value[2].value.trim().length == 0) || (form_value[3].value.trim().length == 0)) {
        return true;
    }
    return false;
}

// verifica se os campos de senha são iguais, retorna true se são iguais
function checkEqualsPasswds(str1, str2) {

    if (new String(str1).valueOf() == new String(str2).valueOf()) {
        return true;
    }
    else {
        return false;
    }

}

// verifica o campo de acordo com a regex informada
function verifyField(pattern, field_name) {
    var reg = new RegExp(pattern);
    return reg.test(field_name);
}
