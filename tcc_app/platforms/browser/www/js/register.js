var btn_form = document.querySelector('#btn_reg')
var divTestev = document.querySelector('div.teste');
var fomr_register = document.querySelector('#register_form');



document.addEventListener('deviceready', function () {

    btn_form.addEventListener('click', SendFormData());
});

function SendFormData() {
    var form_value;
    $("#register_form").submit(function (event) {
        var form_value = ($(this).serializeArray());



        console.log("Nickname: " . concat(form_value[0].value));

        console.log("E-mail: " . concat(form_value[1].value));

        console.log("Senha : " . concat(form_value[2].value));

        console.log("Confirmação de senha: " . concat(form_value[3].value));


        event.preventDefault();
    });

    // event.preventDefault();
}

