let theToken
async function postData(url) {
  const response = await fetch(url, {
    method: "POST", // *GET, POST, PUT, DELETE, etc.
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
    },
    redirect: "follow", // manual, *follow, error
    referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: new URLSearchParams({
      'email': document.getElementById("email").value,
      'password': document.getElementById("password").value
    }) // body data type must match "Content-Type" header
    }).then(r =>  r.json().then(data => ({
      status: r.status, 
      body: data
    })))


  let userData = response.body

  if (response.status === 200) {
    theToken = userData.access_token
    document.getElementById('message').innerHTML = userData.message
    document.getElementById('user').innerHTML = "The user details are:<br>"+userData.user.id+" "+userData.user.name+" "+userData.user.email
    document.getElementById('tokenId').innerHTML = "The token you'll need to include in your request is:<br>"+userData.access_token
    document.getElementById('message').classList.add("alert-success")
    document.getElementById('message').classList.remove("alert-dark")
    document.getElementById('message').classList.remove("alert-danger")
    document.getElementById('user').classList.remove("alert-dark")
    document.getElementById('tokenId').classList.remove("alert-dark")
    document.getElementById('user').classList.add("alert-info")
    document.getElementById('tokenId').classList.add("alert-info")
    //creaExpediente(theToken)
  } else {
    theToken = ""
    document.getElementById('message').innerHTML = userData.password
    document.getElementById('message').classList.add("alert-danger")
    document.getElementById('message').classList.remove("alert-dark")
    document.getElementById('message').classList.remove("alert-success")
    document.getElementById('user').classList.remove("alert-info")
    document.getElementById('tokenId').classList.remove("alert-info")
    document.getElementById('user').innerHTML = ""
    document.getElementById('tokenId').innerHTML = ""
  }
}

function hazLogin(){
  let url = "https://pre.tramits.idi.es/public/index.php/auth/login"
  postData(url).then(creaExpediente(theToken))
 
}

async function obtineExpedientePorID(token) {
  //GET
  let url = "https://pre.tramits.idi.es/public/index.php/expediente/99/2023"
  const response = await fetch(url, {
    method: "POST", // *GET, POST, PUT, DELETE, etc.
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
    },
    redirect: "follow", // manual, *follow, error
    referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: new URLSearchParams({
      'email': document.getElementById("email").value,
      'password': document.getElementById("password").value
    }) // body data type must match "Content-Type" header
    }).then(r =>  r.json().then(data => ({
      status: r.status, 
      body: data
    })))


  let userData = response.body
}

function creaExpediente(API_TOKEN) {
  //POST
  let url = "https://pre.tramits.idi.es/public/index.php/expediente"
  
  const formData = new FormData();
  const fileField = document.querySelector('input[type="file"]');
  formData.append("idExp",'999');
  formData.append("empresa",'nacho s.l.');
  formData.append("nif",'43036826P');
  formData.append("email",'ignacio.llado@idi.es');
  formData.append("convocatoria",'2023');
  formData.append("tipo_tramite",'IDI-ISBA');
  //formData.append("documento", fileField.files[0]);
  fetch(url, {
    method: "POST", // *GET, POST, PUT, DELETE, etc.
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
      'Authorization': `Bearer ${API_TOKEN}`,
    },
    redirect: "follow", // manual, *follow, error
    referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: formData,
})
  .then((response) => response.json())
  .then((result) => {
    console.log("Success:", result);
  })
  .catch((error) => {
    console.error("Error:", error);
  });
}