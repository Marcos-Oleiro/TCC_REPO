const divPasswd = document.querySelector('div.passwd');
const divNew_passwd = document.querySelector('div.new_passwd');

document.addEventListener('deviceready', function() {
  // Função do Jquery - quando o evento submit é realizado, chama a função
  $('#change_passwd_form').submit(changePasswd);
});

function changePasswd() {
  // console.log("foi");

  const form_value = ($(this).serializeArray());
  const empty_fields = checkEmptyFormFields(form_value);
  const pattern_passwd = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/;
  // equals_passwds = checkEqualsPasswds(form_value[1].value.trim(), form_value[2].value.trim());
  // verifyField(pattern_passwd, form_value[1].value.trim());

  if ( !empty_fields ) {
    // console.log('campos ok');

    if (checkEqualsPasswds(form_value[1].value.trim(), form_value[2].value.trim())) {
      if (verifyField(pattern_passwd, form_value[1].value.trim())) {
        // console.log("senhas iguais e no padrão");

        const changePasswd_info = {
          'passwd': form_value[0].value.trim(),
          'new_passwd': form_value[1].value.trim(),
        };
        $.ajaxSetup({
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('tkn'),

          },
        });

        const
          myJSON = JSON.stringify(changePasswd_info);
        const url = 'http://localhost:8080/changepasswd/' + localStorage.getItem('id');
        const jxhr = $.post(url, myJSON)
            .done(function() {
              // informar que a senha foi alterad
              divNew_passwd.textContent = '';
              divPasswd.textContent = 'Senha alterada com sucesso';
            })
            .fail(function() {
              if ( jxhr.status == 400 ) {
                divNew_passwd.textContent = '';
                divPasswd.textContent = 'Senha atual informada incorreta';
                // console.log('senha errada')
              }
              if (jxhr.status == 401) {
                console.log('sem acesso');
              }
            });
      } else {
        // console.log("senhas iguais e fora do padrão");
        divPasswd.textContent = '';
        divNew_passwd.textContent = 'As senhas precisam ser válidas';
      }
    } else {
      // console.log("senhas diferentes")
      divPasswd.textContent = '';
      divNew_passwd.textContent = 'As senhas precisam ser iguais';
    }
  } else {
    // console.log("campos vazios")
    divNew_passwd.textContent = '';
    divPasswd.textContent = 'Campos Vazios';
  }


  event.preventDefault();
}
