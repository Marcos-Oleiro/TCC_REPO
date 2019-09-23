import { Service } from "./service/Service.js"

const divPhoto = document.querySelector('div.photo');
const divName = document.querySelector('div.name');
const img = document.querySelector('img');


document.addEventListener('deviceready', function() {
  loadInfo();
});

function loadInfo(){

  const service = new Service(`home/${localStorage.getItem('id')}`);

  const header = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + localStorage.getItem('tkn'),
  }
  service.doReq('GET', null, header)
  .then(res => {
    // console.log(res);
    // console.log(res.json());
    return res.json();
  })
  .then(res => {
    // console.log(res);
    localStorage.setItem('nickname', (JSON.parse(res)['nickname']));
    localStorage.setItem('description', (JSON.parse(res)['description']));
    if (JSON.parse(res)['photography'] === null) {
      // img.src = "../img/icon_profile5.png";
      img.src = '../img/gohan.jpeg';
    }
    divName.textContent = JSON.parse(res)['nickname'];
  })
  .catch(err => {
    window.location = '../index.html';
  })

}
