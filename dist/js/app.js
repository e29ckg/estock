var url_base = window.location.protocol + '//' + window.location.host;

var jwt = localStorage.getItem("jwt");
if (jwt == null) {
  window.location.href = './login.php'
}else{
  protected(jwt);
}

function protected(jwt) {
  const xhttp = new XMLHttpRequest();
  xhttp.open("POST", url_base +"/estock/api/auth/protected.php");
  xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhttp.setRequestHeader("Authorization", "Bearer " + jwt);
  xhttp.send();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4) {
      const objects = JSON.parse(this.responseText);
      console.log(objects);
      if (objects['status'] == 'ok') {
        localStorage.setItem("jwt", objects['jwt']);
        localStorage.setItem("user_data", objects['data']);
        
      } else {
        Swal.fire({
          title: 'Are you sure?',
          text: objects['message'],
          icon: 'warning',
          // showCancelButton: true,
          confirmButtonColor: '#3085d6',
          // cancelButtonColor: '#d33',
          confirmButtonText: 'Yes !'
        }).then((result) => {
          if (result.isConfirmed) {
            localStorage.removeItem("jwt");
            window.location.href = './login.php';
          }
        })   
      }
    }
  };
  return false;  
}

function logout() {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't Logout!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes !'
  }).then((result) => {
    if (result.isConfirmed) {
      localStorage.removeItem("jwt");      
      localStorage.removeItem("user_data"); 
      window.location.href = './login.php';
    }
  })    
}



Vue.createApp({
  data() {
    return {
      user:'',
      fullname:'2333',
      url_img:'./node_modules/admin-lte/dist/img/user2-160x160.jpg',
    }
  },
  mounted(){
    this.get_fullname()
  },
  methods: {
  get_fullname() {
    this.user = JSON.parse(localStorage.getItem("user_data"));
  }
},
}).mount('#aside')