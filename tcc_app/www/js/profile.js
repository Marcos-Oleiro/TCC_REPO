var divPhoto = document.querySelector('div.photo');
var divName = document.querySelector('div.name');
var txtDesc = document.querySelector('#desc_text');
var img = document.querySelector('img');
var desc_verif_fail = document.querySelector('div.desc_verif_fail');
var desc_verif_suc = document.querySelector('div.desc_verif_suc');

document.addEventListener('deviceready', function (){
    
    divName.textContent = localStorage['nickname'];
    // document.querySelector('#desc_text').value = localStorage['desc'];
    img.src = "../img/icon_profile5.png";
    loadCurrentDesc();
    $("#form_desc").submit(UpDateDesc);
});

function loadCurrentDesc(){
    // localStorage['description'] = "teste";
    if ( localStorage['description'] !== 'null' ){
        txtDesc.textContent = localStorage['description'];
    }
}

function UpDateDesc (){
    
    var new_description = ($(this).serializeArray())[0].value.trim();
    
    if ( new_description.length === 0  ){
        desc_verif_suc.textContent = null;
        desc_verif_fail.textContent = "A nova descrição não poder ser vazia!"
    }
    else{
        
        var new_desc_info = {
            'new_description' : new_description
        };

        $.ajaxSetup({
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': "Bearer " + localStorage.getItem('tkn')
            }
        });
        
        var myJSON = JSON.stringify(new_desc_info);
        var url = "http://localhost:8080/profile/edit/desc" + localStorage.getItem("id");
    
        const jxhr = $.ajax({
            url : url,
            data : myJSON,
            type:'PUT',
        })
        .done(function (){

        })
        .fail( function() {

        })

        
        // console.log("Descrição salva!");
        // desc_verif_fail.textContent = null;
        // desc_verif_suc.textContent = "Descrição salva!"
    }
    
    event.preventDefault();
    
}