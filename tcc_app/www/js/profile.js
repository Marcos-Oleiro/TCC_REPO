var divPhoto = document.querySelector('div.photo');
var divName = document.querySelector('div.name');
var divDesc = document.querySelector('#desc');
var img = document.querySelector('img');

document.addEventListener('deviceready', function (){
    
    divName.textContent = sessionStorage['nickname'];
    // console.log(sessionStorage['desc']);
    // document.querySelector('#desc_text').value = sessionStorage['desc'];
    img.src = "../img/icon_profile5.png";
    $("#form_desc").submit(UpDateDesc);
});

function UpDateDesc (){
    console.log("OI");
    event.preventDefault();
}