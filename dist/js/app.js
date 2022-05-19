
// var this.jwt = localStorage.getItem("this.jwt");
// var user_data = localStorage.getItem("user_data");
// if (this.jwt == null || user_data == null) {
//   window.location.href = './login'
// }  

Vue.createApp({
  data() {
    return {
      url_base:'',
      jwt:'',
      user:'',
      url_img:'./node_modules/admin-lte/dist/img/user2-160x160.jpg',
    }
  },
  mounted(){
    this.url_base = window.location.protocol + '//' + window.location.host + '/estock/'
    this.jwt = localStorage.getItem("jwt")
    this.get_fullname()
    this.ck_protect()
    this.protected()
  },
  methods: {
    get_fullname() {
      this.user = JSON.parse(localStorage.getItem("user_data"));
    },
    ck_protect(){
      // var t = timer
      var t = 60 * 1000
      setInterval(()=> {
        this.protected();
        console.log(t++)
      }, t);
    },

    protected() {
      axios.post(this.url_base + 'api/auth/protected_admin.php',{},{ 
        headers: {
            "Access-Control-Allow-Origin" : "*",
            "Content-type": "Application/json",
            // "Authorization": `Bearer ${this.jwt}`
            "Authorization" : 'Bearer '+ this.jwt 
          }})
            .then(response => {  
                       
                if (response.data.status == 'ok' ) {
                  this.user_data = JSON.stringify(response.data.user_data)            
                }else{
                  // localStorage.removeItem("jwt");
                  // localStorage.removeItem("user_data");    
                  swal.fire({
                    icon: 'error',
                    title: 'ออกจากระบบ',
                    showConfirmButton: true,
                    timer: 1000
                  });
                  setTimeout(function() {
                    // window.location.href = './login';
                  }, 1001); 
                }
            })
            .catch(function (error) {
                console.log(error);
                // localStorage.removeItem("jwt");
                // localStorage.removeItem("user_data"); 
                swal.fire({
                  icon: 'error',
                  title:'ออกจากระบบ',
                  showConfirmButton: true,
                  timer: 1000
                });
                setTimeout(function() {
                  // window.location.href = './login';
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
          axios.post(this.url_base + 'api/auth/logout.php',{},{ 
            headers: {
                "Access-Control-Allow-Origin" : "*",
                "Content-type": "Application/json",
                // "Authorization": `Bearer ${this.jwt}`
                "Authorization" : 'Bearer '+ this.jwt 
              }})
                .then(response => {                            
                    if (response.data.status == 'success' ) {  
                      localStorage.removeItem("jwt");
                      localStorage.removeItem("user_data");    
                      swal.fire({
                        icon: 'success',
                        title: 'ออกจากระบบ',
                        showConfirmButton: true,
                        timer: 1000
                      });
                      setTimeout(function() {
                        window.location.href = './login';
                      }, 1001); 
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    localStorage.removeItem("jwt");
                    localStorage.removeItem("user_data"); 
                    window.location.href = './login';
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
      order_st0:'',
      recs_st0:'',
      menus:[
        {          
          menu_name:'Home',
          menu_class:'',
          menu_url:'index',
          menu_icon_class:'nav-icon fas fa-tachometer-alt',
          menu_badge:'',
        },
        {          
          menu_name:'ใบเบิก',
          menu_class:'',
          menu_url:'orders',
          menu_icon_class:'nav-icon fas fa-shopping-cart',
          menu_badge:'',
        },
        {          
          menu_name:'ใบรับของ',
          menu_class:'',
          menu_url:'recs',
          menu_icon_class:'nav-icon fas fa-receipt',
          menu_badge:'',
        },
        {          
          menu_name:'Products',
          menu_class:'',
          menu_url:'products',
          menu_icon_class:'nav-icon fas fa-store',
          menu_badge:'',
        },
        {          
          menu_name:'Stock',
          menu_class:'',
          menu_url:'stock',
          menu_icon_class:'nav-icon fab fa-shopware',
          menu_badge:'',
        },
        {          
          menu_name:'Report ประจำปี',
          menu_class:'',
          menu_url:'report',
          menu_icon_class:'nav-icon fas fa-tag',
          menu_badge:'',
        }
      ],
      menus_setting:[
        {          
          menu_name:'ประเภทสินค้า',
          menu_class:'',
          menu_url:'catalogs',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'หน่วยนับ',
          menu_class:'',
          menu_url:'units',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        },
        {          
          menu_name:'ร้านค้า',
          menu_class:'',
          menu_url:'store',
          menu_icon_class:'nav-icon fas fa-store',
          menu_badge:'',
        },
        {          
          menu_name:'สมาชิก',
          menu_class:'',
          menu_url:'users',
          menu_icon_class:'nav-icon fas fa-th',
          menu_badge:'',
        }
      ]
    }
  },
  mounted(){
    this.url = window.location.href
    this.url_base = window.location.protocol + '//' + window.location.host + '/estock/'
    this.set_menu()
    this.set_menus_setting()
    this.count_odrs_st0()
    this.count_recs_st0()
    this.ck_protect()
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
    },
    count_odrs_st0(){
      axios.post(this.url_base + 'api/orders/orders_count.php',{data:'st0'})
        .then(response => {
            if(response.data.status) { 
              this.menus[1].menu_badge = response.data.respJSON;
              if(response.data.respJSON === 0){this.menus[1].menu_badge = ''}
            }else {
              this.menus[1].menu_badge = '';
            }
        })
    },
    count_recs_st0(){
      axios.post(this.url_base + 'api/recs/recs_count.php',{data:'st0'})
        .then(response => {
            if(response.data.status) { 
              this.menus[2].menu_badge = response.data.respJSON
              if(response.data.respJSON === 0){this.menus[2].menu_badge = ''}
            }else {
              this.menus[2].menu_badge = '';
            }
        })
    },
    ck_protect(){
      var t = 60 * 1000
      setInterval(()=> {
        this.count_odrs_st0()
        this.count_recs_st0()
      }, t);
    },
    
  },
}).mount('#aside')