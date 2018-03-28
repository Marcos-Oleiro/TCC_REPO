var divPhoto = document.querySelector('div.photo');
var divName = document.querySelector('div.name');
var img = document.querySelector('img');


document.addEventListener('deviceready', function () {
    
    loadInfo();
});

function loadInfo (){
    // var info = {
    //     'id' : sessionStorage['id'] ,
    // };
    // document.querySelector('h1.teste').textContent = sessionStorage['id'];
    // console.log(sessionStorage['email']);
    $('.dropdown-trigger').dropdown();
    $.ajaxSetup({
        headers: {
            // 'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });
    var url = 'http://localhost:8080/home/' + sessionStorage['id'] ;
    // var url = 'http://localhost:8080/home/' ;
    console.log(url);
    $.get(url, function (data){
        // console.log("oi");
        // console.log(JSON.parse(data)['message']);
        // console.log(JSON.parse(data)['nickname']);
        // if (JSON.parse(data)['photography'] === null){
        //     // img.src = "../img/gohan.jpeg";
        // }
        divName.textContent = JSON.parse(data)['nickname'];
    });
    
}