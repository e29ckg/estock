var url_base = window.location.protocol + '//' + window.location.host;

var jwt = localStorage.getItem("jwt");
if (jwt == null) {
  window.location.href = './login.php'
}  

Vue.createApp({
  data() {
    return {
      user:'',
      url_img:'./node_modules/admin-lte/dist/img/user2-160x160.jpg',
    }
  },
  mounted(){
    this.get_fullname()
    this.ck_protect()
    var jwt = localStorage.getItem("jwt")
    this.protected(jwt)
  },
  methods: {
    get_fullname() {
      this.user = JSON.parse(localStorage.getItem("user_data"));
      // this.user = localStorage.getItem("user_data");
    },

    ck_protect(){
      var t = 6 * 60 * 1000
      // var t = 1000
      setInterval(()=> {
        var jwt = localStorage.getItem("jwt");
        this.protected(jwt);
        console.log(t++)
      }, t);
    },

    protected(jwt) {

      axios.post(url_base + '/estock/api/auth/protected.php',{},{ 
        headers: {
            "Access-Control-Allow-Origin" : "*",
            "Content-type": "Application/json",
            // "Authorization": `Bearer ${jwt}`
            "Authorization" : 'Bearer '+ jwt 
          }})
            .then(response => {
              
                // console.log(response.data);
                if (response.data.status == 'ok' ) {
                  user_data = JSON.stringify(response.data.user_data)
                  localStorage.setItem("jwt", response.data.jwt);
                  localStorage.setItem("user_data",user_data);
                }else{
                  localStorage.removeItem("jwt");
                  localStorage.removeItem("user_data");    
                  swal.fire({
                    icon: objects['status'],
                    title: objects['message'],
                    showConfirmButton: true,
                    timer: 1000
                  });
                  setTimeout(function() {
                    window.location.href = './login.php';
                  }, 1001); 
                }
            })
            .catch(function (error) {
                console.log(error);
            });


    },
    logout() {
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
  }
}).mount('#nav')


Vue.createApp({
  data() {
    return {
      user:'',
      url_img:'./node_modules/admin-lte/dist/img/user2-160x160.jpg',
    }
  },
  mounted(){
    // this.get_fullname()
  },
  methods: {
  get_fullname() {
    // this.user = JSON.parse(localStorage.getItem("user_data"));
  }
},
}).mount('#aside')