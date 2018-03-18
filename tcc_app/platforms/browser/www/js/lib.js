
// verifica se há algum campo no formulário vazio, retorna true se houve campo vazio
function checkEmptyFormFields(form_value) {

    if ((form_value[0].value.trim().length == 0) || (form_value[1].value.trim().length == 0) || (form_value[2].value.trim().length == 0) || (form_value[3].value.trim().length == 0)) {
        return "true";
    }
    return "false";
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