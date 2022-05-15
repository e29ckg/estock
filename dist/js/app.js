var url_base = window.location.protocol + '//' + window.location.host + '/estock/'

var jwt = localStorage.getItem("jwt");
var user_data = localStorage.getItem("user_data");
if (jwt == null || user_data == null) {
  window.location.href = './login.html'
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
      // var t = 3000
      setInterval(()=> {
        var jwt = localStorage.getItem("jwt");
        this.protected(jwt);
        console.log(t++)
      }, t);
    },

    protected(jwt) {
      axios.post(url_base + 'api/auth/protected.php',{},{ 
        headers: {
            "Access-Control-Allow-Origin" : "*",
            "Content-type": "Application/json",
            // "Authorization": `Bearer ${jwt}`
            "Authorization" : 'Bearer '+ jwt 
          }})
            .then(response => {  
                       
                console.log(response.data.token);
                if (response.data.status == 'ok' ) {
                  user_data = JSON.stringify(response.data.user_data)
                  // localStorage.setItem("user_data",user_data);                  
                }else{
                  localStorage.removeItem("jwt");
                  localStorage.removeItem("user_data");    
                  swal.fire({
                    icon: 'error',
                    title: response.data.message,
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
                localStorage.removeItem("jwt");
                localStorage.removeItem("user_data"); 
                swal.fire({
                  icon: 'error',
                  title:response.data.message,
                  showConfirmButton: true,
                  timer: 1000
                });
                setTimeout(function() {
                  window.location.href = './login.html';
                }, 1001); 
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
          axios.post(url_base + 'api/auth/logout.php',{},{ 
            headers: {
                "Access-Control-Allow-Origin" : "*",
                "Content-type": "Application/json",
                // "Authorization": `Bearer ${jwt}`
                "Authorization" : 'Bearer '+ jwt 
              }})
                .then(response => {                            
                    if (response.data.status == 'success' ) {  
                      localStorage.removeItem("jwt");
                      localStorage.removeItem("user_data");    
                      swal.fire({
                        icon: response.data.status,
                        title: response.data.message,
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
                    localStorage.removeItem("jwt");
                    localStorage.removeItem("user_data"); 
                    window.location.href = './login.html';
                });

        }
      })    
    }
  }
}).mount('#nav')


Vue.createApp({
  data() {
    return {
      datas:'',
      url:'',
      url_base:'',
      menus:[
        {          
          menu_name:'Home',
          menu_class:'',
          menu_url:'index.php',
          menu_icon_class:'nav-icon fas fa-tachometer-alt',
          menu_badge:'',
        },
        {          
          menu_name:'ใบเบิก',
          menu_class:'',
          menu_url:'orders.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'ใบรับของ',
          menu_class:'',
          menu_url:'recs.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'Products',
          menu_class:'',
          menu_url:'products.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'Stock',
          menu_class:'',
          menu_url:'stock.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        }
      ],
      menus_setting:[
        {          
          menu_name:'ประเภทสินค้า',
          menu_class:'',
          menu_url:'catalogs.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'หน่วยนับ',
          menu_class:'',
          menu_url:'units.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'ร้านค้า',
          menu_class:'',
          menu_url:'store.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'สมาชิก',
          menu_class:'',
          menu_url:'users.php',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        }
      ]
    }
  },
  mounted(){
    this.url = window.location.href
    this.url_base = url_base
    this.set_menu()
    this.set_menus_setting()
  },
  methods: {
    set_menu(){
      for (let i = 0; i < this.menus.length; i++) {
        my_url = this.url_base + this.menus[i].menu_url
        // console.log(my_url)
        if(this.url == my_url){
          this.menus[i].menu_class = 'active'
        }else{
          this.menus[i].menu_class = ''
        }
      }
    },
    set_menus_setting(){
      for (let i = 0; i < this.menus_setting.length; i++) {
        my_url = this.url_base + this.menus_setting[i].menu_url
        // console.log(my_url)
        if(this.url == my_url){
          this.menus_setting[i].menu_class = 'active'
        }else{
          this.menus_setting[i].menu_class = ''
        }
      }
    }
    
  },
}).mount('#aside')