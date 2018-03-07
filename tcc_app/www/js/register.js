var btn_form = document.querySelector('#btn_reg')
var divTestev = document.querySelector('div.teste');



document.addEventListener('deviceready', function() {
    btn_form.addEventListener('click', SendFormData);
});

function SendFormData (){
    
    var xhr = new XMLHttpRequest();
    var form = document.querySelector('#register_form');
    // var fd = new FormData(form); 
    var array_form = $('#register_form').serializeArray();

    console.log(array_form.toString());
    divTestev.textContent = (array_form.toString());
    

}
