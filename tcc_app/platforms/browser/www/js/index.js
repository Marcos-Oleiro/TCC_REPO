

document.addEventListener('deviceready', function () {

    // Função do Jquery - quando o evento submit é realizado, chama a função SendLoginForm
    $("#login_form").submit(SendLoginForm);
});


function SendLoginForm () {

    var form_value;
    var nickname, email, senha;
    var form_value = ($(this).serializeArray());
    var pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var pattern_passwd = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/;


    console.log(form_value);
    var empty_fields = checkEmptyFormFields(form_value);
    console.log(empty_fields);
    // if (empty_fields){
    //     console.log("true"); 
        
    // }
    // else{
    //     console.log("false"); 
        
    // }
    event.preventDefault();

}

// verifica se há algum campo no formulário vazio, retorna true se houve campo vazio
function checkEmptyFormFields(form_value) {

    if ((form_value[0].value.trim().length == 0) || (form_value[1].value.trim().length == 0) ) {
        return 'true';
    }
    return 'false';
}


// verifica o campo de acordo com a regex informada
function verifyField(pattern, field_name) {
    var reg = new RegExp(pattern);
    return reg.test(field_name);
}