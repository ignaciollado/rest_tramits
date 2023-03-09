<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<!-- <script type="text/javascript" src="../../../public/assets/js/utilities.js"></script>	 -->
<div class="container">

<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>


    <?= csrf_field() ?>
    <div class="row">
      <div class="col">
        <div class="mb-3">
          <label for="user" class="form-label">Email address</label>
          <input type="email" class="form-control" id="user" placeholder="name@example.com" value="nachollv@hotmail.com">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" placeholder="name@example.com" value="12345678">
        </div>
        <button class='btn btn-primary' onclick="javaScript: loginUser();">Login</button>
      </div>
      <div class="col">
        <div class="mb-3">
          <label for="idExp" class="form-label">IdExp</label>
          <input type="text" class="form-control" id="idExp" name="idExp" placeholder="exped number" value="1001">
        </div>
        <div class="mb-3">
          <label for="convocatoria" class="form-label">Convocatoria</label>
          <input type="text" class="form-control" id="convocatoria" name="convocatoria" placeholder="Convocatoria" value="2023" readonly disabled maxlength="4" minlength="4">
        </div>  
        <div class="mb-3">
          <label for="tipo_tramite" class="form-label">Línea de ayuda</label>
          <input type="text" class="form-control" id="tipo_tramite" name="tipo_tramite" placeholder="tipo_tramite" value="IDI-ISBA" readonly disabled maxlength="4" minlength="4">
        </div>    
        <div class="mb-3">
          <label for="nif" class="form-label">nif</label>
          <input type="text" class="form-control" id="nif" name="nif" placeholder="nif" value="43036826P" maxlength="9" minlength="9">
        </div> 
        <div class="mb-3">
          <label for="empresa" class="form-label">empresa</label>
          <input type="text" class="form-control" id="empresa" name="empresa" placeholder="empresa" value="nachollv s.l." minlength="4">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">email empresa</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="email" value="nachollv@hotmail.com" minlength="4">
        </div>                
        <div class= "mb-3">
          <label for="myFile" class="form-label">Upload document</label>
          <input type="file" class="form-control" id="myFile" name="myFile[]" multiple required placeholder="select a file" accept="image/png, image/jpeg, .pdf" value="">
        </div>
        <button class='btn btn-primary' onclick="javaScript: creaExpediente(theToken);">Create expediente</button>

        <div class="btn-group" role="group" aria-label="Basic example">

          <button class='btn btn-primary' onclick="javaScript: obtineExpedientes(theToken);">Get ALL expedientes</button>
          <button class='btn btn-primary' onclick="javaScript: obtineOneExpediente(theToken);">Get ONE expediente</button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
          <div class="alert alert-dark" role="alert" id='userStatus'>unknown</div>
          <div class="alert alert-dark" role="alert" id='tokenId'>unknown</div>
          <div class="alert alert-dark" role="alert" id='message'>unknown</div>
      </div>
    </div>

</div>

<script>
let theToken = ""
let url = "https://pre.tramits.idi.es/public/index.php/"
let endPoint = ""

async function hazLogin(endPoint) {
  //POST
  const response = await fetch(endPoint, {
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
      'email': document.getElementById("user").value,
      'password': document.getElementById("password").value
    }) // body data type must match "Content-Type" header
    }).then(r =>  r.json().then(data => ({
      status: r.status, 
      body: data
    })))
  let userData = response.body
  let status = response.status

  if (status === 200) {
    theToken = userData.access_token
    document.getElementById('message').innerHTML = userData.message
    document.getElementById('userStatus').innerHTML = "The user details are:<br>"+userData.user.id+" "+userData.user.name+" "+userData.user.email
    document.getElementById('tokenId').innerHTML = "The token you'll need to include in your request is:<br>"+userData.access_token
    document.getElementById('message').classList.add("alert-success")
    document.getElementById('message').classList.remove("alert-dark")
    document.getElementById('message').classList.remove("alert-danger")
    document.getElementById('userStatus').classList.remove("alert-dark")
    document.getElementById('tokenId').classList.remove("alert-dark")
    document.getElementById('userStatus').classList.add("alert-info")
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

function loginUser() {
  endPoint = `${url}auth/login`
  hazLogin(endPoint)/* .then(creaExpediente(theToken).then(obtineExpedientePorID(theToken))) */
}

async function creaExpediente(API_TOKEN) {
  //POST

  endPoint = `${url}expediente/store`

  const formData = new FormData();
  const fileField = document.querySelector('input[type="file"]');
  idExp =document.getElementById('idExp').value
  empresa =document.getElementById('empresa').value
  nif =document.getElementById('nif').value
  email =document.getElementById('email').value
  convocatoria =document.getElementById('convocatoria').value
  tipo_tramite =document.getElementById('tipo_tramite').value
  doc =document.querySelector('input[type="file"]')
  console.log (doc)

  param = {
            idExp: idExp,
            empresa: empresa,
            nif: nif,
            email: email,
            convocatoria: convocatoria,
            tipo_tramite: tipo_tramite,
            doc: doc
          };

  param = JSON.stringify( param )
  formData.append('idExp', document.getElementById("idExp").value);
  formData.append('empresa', document.getElementById("empresa").value);
  formData.append('nif', document.getElementById("nif").value);
  formData.append('email', document.getElementById("email").value);
  formData.append('convocatoria', document.getElementById("convocatoria").value);
  formData.append('tipo_tramite', document.getElementById("tipo_tramite").value);
  formData.append('doc', document.getElementById("myFile").value);

  const response = await fetch(endPoint, {
    method:"POST", // *GET, POST, PUT, DELETE, etc.
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
      /* 'Content-Type': 'application/json', */
      'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
      'Content-Type': 'multipart/form-data',
      /* 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', */
      'Authorization': `Bearer ${API_TOKEN}`,
      'User-Agent': 'idi-isba'
    },
    redirect: "follow", // manual, *follow, error
    referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: formData, 
  })
  .then(r => r.body)
  .then((rb) => {
    const reader = rb.getReader();

    return new ReadableStream({
      start(controller) {
        // The following function handles each data chunk
        function push() {
          // "done" is a Boolean and value a "Uint8Array"
          reader.read().then(({ done, value }) => {
            // If there is no more data to read
            if (done) {
              /* console.log("done", done); */
              controller.close();
              return;
            }
            // Get the data and send it to the browser via the controller
            controller.enqueue(value);
            // Check chunks by logging to the console
            /* console.log(done, value); */
            push();
          });
        }

        push();
      },
    });
  })
  .then((stream) =>
    // Respond with our stream
    new Response(stream, { headers: { "Content-Type": "application/json" } }).text()
  )
  .then((result) => {
    // Do things with result
    return result
  });
  /* console.log (response, typeof response) */
  document.getElementById('message').innerHTML = response

}

async function obtineExpedientes(API_TOKEN) {
  //POST
  endPoint = `${url}expediente/getall`
  const response = await fetch(endPoint, {
    method: "POST", // *GET, POST, PUT, DELETE, etc.
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
     /*  'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', */
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${API_TOKEN}`,
    },
    redirect: "follow", // manual, *follow, error
    referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    }).then(r => r.json().then(data => ({
      status: r.status, 
      body: data
    })))
  let expData = response.body
  let message = document.getElementById('message')

  document.getElementById('message').classList.remove('alert-danger')
  document.getElementById('message').classList.add('alert-success')

  while (message.firstChild) {
        message.removeChild(message.lastChild);
      }

  expData.expedientes.forEach(element => {
    theContainer = document.createElement('div')
    theContainer.classList.add('container')
    theContainer.classList.add('text-left')
    message.appendChild(theContainer)

    theRow = document.createElement('div')
    theRow.classList.add('row')
    
    theRow.innerHTML = `<div class="col"><a href="${element.id}">${element.idExp}/${element.convocatoria}</a></div>
                                                      <div class="col">${element.nif}</div>
                                                      <div class="col">${element.empresa}</div>
                                                      <div class="col">${element.email}</div>
                                                      <div class="col">${element.tipo_tramite}</div>
                                                      <div class="col">${element.doc}</div>`
    theContainer.appendChild(theRow)
  });
}

async function obtineOneExpediente(API_TOKEN) {
  //GET
  endPoint = `${url}expediente/getone/100/2023`
  const response = await fetch(endPoint, {
    method: "GET", // *GET, POST, PUT, DELETE, etc.
    mode: "cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
      'Authorization': `Bearer ${API_TOKEN}`,
    },
    redirect: "follow", // manual, *follow, error
    referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    }).then(r =>  r.json().then(data => ({
      status: r.status, 
      body: data
    })))


  let expData = response.body
  let respStatus = response.status
  document.getElementById('message').classList.remove('alert-dark')
  if (respStatus === 200) {
    document.getElementById('message').classList.remove('alert-danger')
    document.getElementById('message').classList.add('alert-success')
    document.getElementById('message').innerHTML = response.body.expediente.idExp+"/"+response.body.expediente.convocatoria+" "+response.body.expediente.empresa
  } else {
    document.getElementById('message').classList.remove('alert-success')
    document.getElementById('message').classList.add('alert-danger')
    document.getElementById('message').innerHTML = response.body.message
  }
}
</script>