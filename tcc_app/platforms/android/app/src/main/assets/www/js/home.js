var divPhoto = document.querySelector('div.photo');
var divName = document.querySelector('div.name');
var img = document.querySelector('img');



document.addEventListener('deviceready', function () {
    loadInfo();    
});

function loadInfo (){
    // $('.dropdown-trigger').dropdown();
    $.ajaxSetup({
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization' : "Bearer " + localStorage.getItem('tkn')
        }
    });
    var url = 'http://localhost:8080/home/' + localStorage.getItem('id') ;
    const jxhr = $.get(url)
    .done(function (data) {
        localStorage.setItem('nickname',(JSON.parse(data)['nickname']));
        localStorage.setItem('description', (JSON.parse(data)['description']));
        if (JSON.parse(data)['photography'] === null){
            // img.src = "../img/icon_profile5.png";
        
            img.src = "../img/gohan.jpeg";
        }
        divName.textContent = JSON.parse(data)['nickname'];
    })
    .fail(function (){
        window.location = "../index.html";
    })    
}
