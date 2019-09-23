import { Service } from "./service/Service.js";

const divEmail = document.querySelector('div.email');
const divPasswd = document.querySelector('div.passwd');

const error = new URLSearchParams(location.search);

document.addEventListener('deviceready', function () {
  // Função do Jquery - quando o evento submit é realizado, chama a função SendLoginForm
  $('#login_form').submit(SendLoginForm);


  if (error.get('error') == 1) {
    divEmail.textContent = 'Log In expirado, favor logar novamente';
  }
});


function SendLoginForm() {

  event.preventDefault();

  // Declaração de variáveis
  let form_value;
  let email; let passwd;
  form_value = ($(this).serializeArray());
  const pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  const pattern_passwd = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/;
  // Final da declaração das variáveis


  const empty_fields = checkEmptyFormFields(form_value);
  divEmail.textContent = '';
  divPasswd.textContent = '';

  // Se os campos tiverem preenchidos, deve entrar nesse IF
  if (!empty_fields) {
    email = form_value[0].value.trim();
    passwd = form_value[1].value.trim();

    // se o campo e-mail não é válido, entra nesse IF
    if (!verifyField(pattern_email, email)) {
      // console.log("E-mail não é válido");
      divEmail.textContent = 'O e-mail não é válido';
      // event.preventDefault();
    }
    // se o campo senha não é válido, entra nesse IF
    else if (!verifyField(pattern_passwd, passwd)) {
      // console.log("Senha não válida");
      divPasswd.textContent = 'A senha informada não é valida';
      // event.preventDefault();
    }
    // Entra aqui se os dois campos estiverem preenchidos corretamente.
    else {
      // console.log("Campos OK");
      const login_info = {
        'email': email,
        'passwd': passwd,
      };
      const service = new Service('login');
      const myJSON = JSON.stringify(login_info);
      const headers = {
        'Accept': 'application/json, text/plain, */*',
        'Content-Type': 'application/json',
      }
      let status;
      // userService.logIn(myJSON)
      service.doReq('POST',myJSON,headers)
        .then(res => {
          status = res.status;
          if (status === 200) {
            localStorage.setItem('id', res.headers.get('id'));
          }
          return res.json();
        })
        .then(res => {

          if (status === 200) {
            localStorage.setItem('tkn', res.split(':')[1].split('}')[0].split('"')[1]);
            window.location = 'html/home.html'
          }
          // console.log(res['erro']);
          divEmail.textContent = res['erro'];
        })
    }
  }
  else { // Campos Vazios
    event.preventDefault();
    divEmail.textContent = 'Os campos devem ser preenchidos';
  }
}