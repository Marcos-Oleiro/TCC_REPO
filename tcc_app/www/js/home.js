var divPhoto = document.querySelector('div.photo');
var divName = document.querySelector('div.name');
var img = document.querySelector('img');



document.addEventListener('deviceready', function () {
    loadInfo();
    // $('#profile_button').click(loadProfile);
    // $('#passwd_button').click(loadPasswdScreen);

});

function loadInfo (){
    // $('.dropdown-trigger').dropdown();
    $.ajaxSetup({
        headers: {
            // 'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });
    var url = 'http://localhost:8080/home/' + sessionStorage['id'] ;
    // var url = 'http://172.16.50.119:8080/home/' + sessionStorage['id'] ;
    // var url = 'http://localhost:8080/home/' ;
    // console.log(url);
    $.get(url, function (data){
        // console.log("oi");
        sessionStorage.setItem('desc',(JSON.parse(data)['description']));
        sessionStorage.setItem('nickname',(JSON.parse(data)['nickname']));
        if (JSON.parse(data)['photography'] === null){
            img.src = "../img/icon_profile5.png";
        
            // img.src = "../img/gohan.jpeg";
        }
        
        divName.textContent = JSON.parse(data)['nickname'];
    });
    
}

// function loadProfile(){
//     console.log("Olá");
// }

// function loadPasswdScreen(){
//     console.log("tchau!");
// }