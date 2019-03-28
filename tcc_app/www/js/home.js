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
            // 'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization' : "Bearer " + sessionStorage.getItem('tkn')
        }
    });
    var url = 'http://localhost:8080/home/' + sessionStorage['id'] ;
    const jxhr = $.get(url)
    .done(function (data) {
        sessionStorage.setItem('desc',(JSON.parse(data)['description']));
        sessionStorage.setItem('nickname',(JSON.parse(data)['nickname']));
        sessionStorage.setItem('description', (JSON.parse(data)['description']));
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
