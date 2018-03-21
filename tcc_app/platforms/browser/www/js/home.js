document.addEventListener('deviceready', function () {
    loadInfo();
});

function loadInfo (){
    // var info = {
    //     'email' : sessionStorage['email'] ,
    // };
    console.log(sessionStorage['email']);
    $.ajaxSetup({
        headers: {
            // 'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });
    var url = 'http://localhost:8080/users/home/' + sessionStorage['email'];
    console.log(url);
    $.get(url, function (data){
        // console.log(JSON.parse(data));
    });
}