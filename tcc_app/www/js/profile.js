import { Service } from "./service/Service.js";

const divPhoto = document.querySelector('div.photo');
const divName = document.querySelector('div.name');
const txtDesc = document.querySelector('#desc_text');
const img = document.querySelector('img');
const desc_verif_fail = document.querySelector('div.desc_verif_fail');
const desc_verif_suc = document.querySelector('div.desc_verif_suc');

document.addEventListener('deviceready', function() {
  divName.textContent = localStorage['nickname'];
  // document.querySelector('#desc_text').value = localStorage['desc'];
  // img.src = "../img/icon_profile5.png";
  loadCurrentDesc();
  $('#form_desc').submit(UpDateDesc);
});

function loadCurrentDesc() {
  // localStorage['description'] = "teste"; 
  if ( localStorage['description'] !== 'null' ) {
    txtDesc.textContent = localStorage['description'];
  }
}

function UpDateDesc() {
  event.preventDefault();
  const new_description = ($(this).serializeArray())[0].value.trim();

  if ( new_description.length === 0 ) {
    desc_verif_suc.textContent = null;
    desc_verif_fail.textContent = 'A nova descrição não poder ser vazia!';
  } else {

    const new_desc_info = {
      'new_description': new_description,
    };

    const service = new Service(`profile/edit/desc/${localStorage.getItem("id")}`);
    const myJSON = JSON.stringify(new_desc_info);
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('tkn'),
    }

    let status;

    service.doReq('PUT',myJSON,headers)
    .then(res => {
      console.log(res);
    })
    





    // $.ajaxSetup({
    // });

    // const myJSON = JSON.stringify(new_desc_info);
    // const url = 'http://localhost:8080/profile/edit/desc/' + localStorage.getItem('id');

    // // console.log(myJSON);

    // const jxhr = $.post(url, myJSON)
    //     .done( function() {
    //       desc_verif_suc.textContent = 'Descrição alterada!';
    //       desc_verif_fail.textContent = null;
    //     })
    //     .fail( function() {
    //       window.location = '../index.html?error=1';
    //     });
  }

  event.preventDefault();
}
