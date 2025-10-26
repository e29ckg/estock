var url_base = window.location.protocol + '//' + window.location.host;

document.getElementById("form").addEventListener("submit", function(event){
      event.preventDefault()
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;
      const password2 = document.getElementById("password2").value;
      const email = document.getElementById("email").value;
      const fullname = document.getElementById("fullname").value;
        
      if(username != '' && email != '' && fullname != '' && password == password2){
        const xhttp = new XMLHttpRequest();
        xhttp.open("POST", "./api/auth/register.php");
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhttp.send(JSON.stringify({
          "username": username,
          "password": password,
          "email": email,
          "fullname": fullname,
        }));
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4) {
            const objects = JSON.parse(this.responseText);
            console.log(objects);
            if (objects['status'] == 'ok') {
                Swal.fire({
                    text: objects['message'],
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.setItem("jwt", objects['jwt']);
                        window.location.href = './index.php';
                    }
              });
            } else {
              Swal.fire({
                text: objects['message'],
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          }
        };
        return false;
      }else{
       
        alert('กรุณาตรวจสอบการป้อนข้อมูล');
      }
});
