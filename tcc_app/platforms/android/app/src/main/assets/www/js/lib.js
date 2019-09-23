function checkEmptyFormFields(form_value) {
  const size_form = form_value.length;

  // console.log(size_form);
  let i = 0;
  for ( i; i < size_form; i++) {
    // console.log(i);
    if ( form_value[i].value.trim() == 0) {
      return true;
    }
  }
  return false;
}


// verifica se os campos de senha são iguais, retorna true se são iguais
function checkEqualsPasswds(str1, str2) {
  if (new String(str1).valueOf() == new String(str2).valueOf()) {
    return true;
  } else {
    return false;
  }
}

// verifica o campo de acordo com a regex informada
function verifyField(pattern, field_name) {
  const reg = new RegExp(pattern);
  return reg.test(field_name);
}
