var divPhoto = document.querySelector('div.photo');
var divName = document.querySelector('div.name');
var txtDesc = document.querySelector('#desc_text');
var img = document.querySelector('img');
var desc_verif_fail = document.querySelector('div.desc_verif_fail');
var desc_verif_suc = document.querySelector('div.desc_verif_suc');

document.addEventListener('deviceready', function (){
    
    divName.textContent = sessionStorage['nickname'];
    // document.querySelector('#desc_text').value = sessionStorage['desc'];
    img.src = "../img/icon_profile5.png";
    loadCurrentDesc();
    $("#form_desc").submit(UpDateDesc);
});

function loadCurrentDesc(){
    if ( sessionStorage['description'] !== 'null' ){
        txtDesc.textContent = sessionStorage['description'];
    }
}


function UpDateDesc (){
    var new_description = ($(this).serializeArray());

    if ( new_description[0].value.trim().length === 0  ){
        console.log("Descrição em branco.");
        desc_verif_suc.textContent = null;
        desc_verif_fail.textContent = "A nova descrição não poder ser vazia!"
        
    }
    else{
        console.log("Descrição salva!");
        desc_verif_fail.textContent = null;
        desc_verif_suc.textContent = "Descrição salva!"
        var new_desc_info = {
            'id' : sessionStorage['id'],
            'desc' : new_description
        };
        $.ajaxSetup({
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        var myJSON = JSON.stringify(new_desc_info);
        var url = "http://localhost:8080/change_desc";
        
    }
    
    event.preventDefault();
}