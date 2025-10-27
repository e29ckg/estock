<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Stock</title>
  
  <?php include "./layouts/head.php";?>  
  
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" >
  <!-- Navbar -->
  <?php include "./layouts/nav.php";?>
  <?php include "./layouts/aside.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="appOrder" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">ใบเบิก</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Order</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order ประวัติการเบิก</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click.prevent="b_Order_insert()" ref="m_show">เพิ่มใบเบิกของ</button>                  
                                              
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>code</th>
                      <th>วันที่เบิก</th>
                      <th>ผู้เบิก</th>
                      <th width="10%">สถานะ</th>
                      <th width="10%"></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                      <td>{{data.order_id}}</td>
                      <td :data-date="data.order_date">{{date_thai(data.order_date)}}</td>
                      <td>{{data.fullname}}</td>
                      <td>
                        <span class="badge bg-danger" v-if="data.st == 0">รอตรวจสอบ</span>
                        <span v-if="data.st == 1" class="badge bg-primary">อนุมัติแล้ว</span>
                      </td>
                      <td>
                        <button class="btn btn-block btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal4" v-if="data.st == 1" @click="b_Check(data.order_id)">รายละเอียด</button>                      
                        <button class="btn btn btn-block btn-warning btn-xs" @click.prevent="b_Order_update(data.order_id)" v-if="data.st == 0" >Update</button>  
                        <button class="btn btn-block btn-danger btn-xs" @click.prevent="destroy_Order(data.order_id)" v-if="data.st == 0">Delete</button>  
                        <button class="btn btn-block btn-success btn-xs" @click.prevent="order_print(data.order_id)" >พิมพ์</button>  
                      </td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>          
        </div>
      </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <!-- <form @submit.prevent="b_Order_save()">             -->
        <div class="modal-content">         
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ผู้เบิก : {{Ord.order_own}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click.prevent="b_Order_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row" hidden> 
              <div class="col-sm-6">
                <div class="form-group">
                  <label>ผู้เบิก</label>
                  <!-- <input type="text" name="order_own" id="order_own" class="form-control" v-model="Ord.order_own"> -->
                  <select v-model="Ord.user_id" class="form-control" aria-label="Default select example" required>
                    <option v-for="u in users" :value="u.user_id">{{u.fullname}}</option>
                  </select>
                  
                </div>
              </div>  
              <div class="col-sm-6">
                <div class="form-group">
                  <label>วันที่เบิก</label>
                  <input type="date" name="datepicker" id="datepicker" class="form-control" v-model="Ord.order_date" required>
                </div>
              </div>
            </div>  
            <!-- {{Ord}} -->
            <table class="table">
              <thead class="text-center bg-lime">
                <tr>
                  <td >#</td>
                  <td width="30%">สินค้า</td>
                  <td>หน่วยนับ</td>
                  <td>จำนวนที่มี</td>
                  <td>จำนวนที่ขอเบิก</td>
                  <td>หมายเหตุ</td>
                  <td></td>

                </tr>
              </thead>
              <tbody>
                <tr v-for="orl,index in Ord_lists">
                  <td>{{index + 1}}</td>
                  <td>
                    <div class="input-group">
                      <input type="text" class="form-control" v-model="orl.pro_id" hidden>
                      <input type="text" class="form-control text-right" v-model="orl.pro_name" placeholder="เลือกสินค้า-->" disabled>
                      <div class="input-group-append">
                        <button class="input-group-text" data-toggle="modal" data-target="#exampleModal2" @click.prevent="b_pro_show(index)" ><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </td>
                  <td>
                    <input type="text" class="form-control text-center" v-model="orl.unit_name" placeholder="หน่วยนับ" disabled>
                  </td>
                  <td>
                     <input v-if="orl.instock" type="text" class="form-control text-center" :value="formatCurrency0(orl.instock)" disabled>
                     <input v-else type="text" class="form-control text-center" :value="0" disabled>
                  </td>
                  <td>
                     <input type="number" class="form-control text-center" v-model="orl.qua"  placeholder="จำนวน" v-if="orl.instock > 0" @keyup="keyup_qua(index)" @change="keyup_qua(index)" >
                     <input type="number" class="form-control text-center" v-model="orl.qua"  placeholder="จำนวน" v-else disabled>
                  </td>                  
                  <td>
                     
                  </td>                  
                  
                  <td >
                    <button v-if="index +1  == Ord_lists.length && index > 0" @click.prevent="b_orl_del(index)" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                  </td>
                </tr>  
              </tbody>
              <tfoot class="">
                <tr>
                  <td colspan="6">
                    <button class="btn btn-success" @click.prevent="b_orl_plus()">
                      <i class="fas fa-plus"></i> เพิ่ม
                    </button>  
                  </td>                 

                </tr>            
              </tfoot>
            </table>
            <!-- {{Ord_lists}} -->
            
          </div>
          <div class="modal-footer">
            {{Ord.order_own}} 
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click.prevent="b_Order_close()">Close</button>
            <button type="submit" class="btn btn-primary" @click.prevent="b_Order_save()">Save changes</button>     
          </div>
          <!-- </form> -->
            <!-- {{Order}} -->            
        </div>
      </div>
    </div>
    <!-- /**** */ -->

    <!-- Modal2 Products -->
    <div class="modal fade" id="exampleModal2" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable"  role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel2">เลือกรายการสินค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m2_close" >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <!-- <input type="text" v-model="q" @keyup="ch_search_pro" ref="search" placeholder="Search."> -->
          <div class="row mb-3">
            <div class="col-sm-12">
              <div class="input-group">
                <input type="text" v-model="q" @keyup="ch_search_pro" ref="search" class="form-control text-center" placeholder="Search..">
                <div class="input-group-append">
                  <button class="input-group-text">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

            </div>
          </div>

          <div class="col-md-12 col-sm-12 col-12" v-for="dp in products"  @click.prevent="select_pro(dp.pro_id,dp.pro_name,dp.unit_name,dp.instock,dp.min)">
            <div class="info-box">
              <!-- <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span> -->
              <img v-if="dp.img" :src="'./uploads/' + dp.img" alt="data.img" class="float-left" width="60" >
              <img v-else src="./dist/img/pro_no_pic.jpg" alt="No-pic" class="float-left" width="60" >
              <div class="info-box-content">
                <span class="info-box-text">{{dp.pro_name}}</span>
                <span class="info-box-number">{{dp.instock}} {{dp.unit_name}}</span>
              </div>
            </div>
          </div>
          
          <!-- {{products}} -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  >Close</button>
          </div>
            <!-- {{Order}} -->            
        </div>
      </div>
    </div>
    <!-- //****************************** */ -->

    <!-- Modal3 ตรวจสอบ -->
    <div class="modal fade" id="exampleModal3" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">ร้านค้า</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="m3_close_click"  ref="m3_close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"> 
              <div class="invoice p-3 mb-3">
                <!-- {{Order}} -->
                <div class="row">
                  <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> ใบเบิกวัสดุ.
                    <small class="float-right">Date: {{Ord.order_date}}</small>
                  </h4>
                  </div>
                </div>
                <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                    ผู้เบิก
                    <address>
                      <strong>{{Ord.order_own}}</strong><br>
                    </address>
                </div>

                <div class="col-sm-4 invoice-col">
                  <!-- To
                  <address>
                  <strong>...</strong><br>
                  </address> -->
                </div>

                <div class="col-sm-4 invoice-col">
                  <b>CODE #{{Ord.order_id}}</b><br>
                </div>
              </div>

              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Product</th>
                        <th>หน่วยนับ</th>
                        <th>จำนวนที่มี</th>
                        <th>จำนวนที่ขอเบิก</th>
                        <th>หมายเหตุ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="orl,index in Ord_lists" class="text-center">
                        <td>{{index + 1 }}</td>
                        <td class="text-left">{{orl.pro_name}}</td>
                        <td>{{orl.unit_name}}</td>
                        <td>{{formatCurrency0(orl.qua_for_ord)}}</td>
                        <td>{{formatCurrency0(orl.qua)}}</td>
                        <td>
                          <button class="btn btn-danger" v-if="Number(orl.qua) > Number(orl.qua_for_ord) || Number(orl.qua) == 0 ">
                            <i class="fas fa-times"></i>
                          </button>
                          
                          {{orl.comment}}
                        </td>
                      </tr>            
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row no-print">
                <div class="col-12">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="m3_close_click" >Close</button>                  
                  <button  type="button" class="btn btn-success float-right "  v-if="Ord.st == 0" @click="b_active()">
                    <i class="far fa-credit-card"></i>
                    อนุมัติการเบิก
                  </button>            
                            
                </div>
              </div>
            </div>
          </div>
                      
        </div>
      </div>
    </div>
    <!-- //****************************** */ -->

    <!-- //********** รายละเอียด *********** */ -->
    <div class="modal fade" id="exampleModal4"  data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ร้านค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"> 
            <div class="invoice p-3 mb-3">
              <!-- {{Order}} -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> ใบเบิกวัสดุ.
                    <small class="float-right">Date: {{Ord.order_date}}</small>
                  </h4>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  ผู้เบิก
                  <address>
                    <strong>{{Ord.order_own}}</strong><br>
                  </address>
                </div>

                <div class="col-sm-4 invoice-col">
                  <!-- To
                  <address>
                  <strong>...</strong><br>
                  </address> -->
                </div>

                <div class="col-sm-4 invoice-col">
                  <b>CODE #{{Ord.order_id}}</b><br>
                </div>
              </div>

              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Product</th>
                        <th>หน่วยนับ</th>
                        <th>จำนวนที่ขอเบิก</th>
                        <th>จำนวนที่จ่าย</th>
                        <th>หมายเหตุ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="orl,index in Ord_lists" class="text-center">
                        <td>{{index + 1 }}</td>
                        <td class="text-left">{{orl.pro_name}}</td>
                        <td>{{orl.unit_name}}</td>
                        <td>{{orl.qua}}</td>
                        <td>{{orl.qua_pay}}</td>
                        <td>
                          <!-- <button class="btn btn-danger" v-if="orl.qua > orl.instock || orl.qua == 0"><i class="fas fa-times"></i></button> -->
                          {{orl.comment}}

                        </td>
                      </tr>            
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row no-print">
                <div class="col-12">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >Close</button>                      
                </div>
              </div>
            </div>
          </div>                      
        </div>
      </div>
    </div>
    


  </div>
  <!-- END APP -->
  <?php include "./layouts/footer.php";?>
</div>
<?php include "./layouts/footer2.php";?>

<script>
  //requireAuth()
  const token = getJWT();
  Vue.createApp({
    data() {
      return {
        this:'',
        datas:'',
        q:'',
        message: 'Hello Vue!',
        stores:'',
        products:'',
        Ord:{order_id:'', order_own:'',ord_app:'', order_date:'', ord_pay:'',ord_pay_name:'',comment:'',action:'insert'},
        Ord_lists:[{pro_id:'', pro_name:'', unit_name:'', qua:''}],
        select_pro_index:'',
        users:'',
        ck_order:''
        
      }
    },
    mounted(){
      this.url_base ='.' ;
      this.get_Orders()
      //this.get_Products()
      //this.get_users()
    },
    methods: { 
      order_print(order_id){
         window.open("orders-print.php?order_id=" + order_id, "_blank");

      },    
      get_Orders() {
        const user_id = getUserData().user_id;
        axios.get(`api/orders/get_orders_user.php?user_id=${user_id}`, {
           headers: { Authorization: `Bearer ${token}` }
           })
          .then(res => {
            this.datas = res.data.orders || [];
          })
          .catch(err => this.handleAuthError(err));
      },

     
      
      get_Products() {
        axios.get(this.url_base + '/api/products/get_products.php', {
          headers: { Authorization: `Bearer ${token}` }
        })
        .then(response => {
          if (response.data.status && response.data.respJSON) {
            this.products = response.data.respJSON;
          } else {
            console.warn("API returned no products:", response.data.message);
          }
        })
        .catch(error => {
          console.error("API error:", error);
        });
      },

      get_Order(order_id) {
        axios.post(
          this.url_base + '/api/orders/get_order.php',
          { order_id: order_id }, 
          { headers: { Authorization: `Bearer ${token}` }}
        )
        .then(response => {
          if (response.data.status && response.data.respJSON) {
            this.Ord = {...response.data.respJSON,action : 'update'};
           
          } else {
            console.warn("API returned no order:", response.data.message);
          }
        })
        .catch(error => {
          console.error("API error:", error);
        });this.Ord
      },
      get_Ord_list(order_id){
        axios.post(this.url_base + '/api/orders/get_orders_list.php',
        {order_id:order_id},
        { headers: { Authorization: `Bearer ${token}` }})
            .then(response => {
                if (response.data.status) {
                  this.Ord_lists = response.data.respJSON;    
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_Order_insert(){
        this.b_Order_close();
        var user_data = getUserData();
        this.Ord.order_own = user_data.fullname
        this.Ord.user_id = user_data.user_id
        this.Ord.order_date = new Date().toISOString().substr(0,10)
        console.log(this.Ord.order_date)
      },  
      b_Order_update(order_id){        
        this.$refs['m_show'].click()
        this.get_Order(order_id)
        this.get_Ord_list(order_id)
      },
      b_Check(order_id,str_id){
        this.get_Order(order_id)
        this.get_Ord_list(order_id)
      },
      m3_close_click(){
        this.ck_order = 0
      },
      b_active(){
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, it!'
              }).then((result) => {
                if (result.isConfirmed) {
                  var jwt = localStorage.getItem("jwt");
                  this.Ord.action='active'
                  axios.post(this.url_base + '/api/orders/orders_active.php',
                    {Ord:this.Ord, Ord_lists:this.Ord_lists},
                    { headers: {"Authorization" : `Bearer ${token}}`}})
                    .then(response => {
                      if (response.data.status){
                        Swal.fire({
                          icon: 'success',
                          title: response.data.message,
                          showConfirmButton: false,
                          timer: 1500
                        });
                        this.get_Orders();
                        this.ck_order = 0
                        this.$refs['m3_close'].click();                    
                      }else{
                        Swal.fire({
                          icon: 'error',
                          title: response.data.message,
                          showConfirmButton: false,
                          timer: 1500
                        })
                       }
                  }).catch(function (error) {
                      console.log(error);
                    });
                }
              });
      }, 

      b_Order_save(){
        if(this.Ord.user_id != '' && this.Ord.order_date != '' && this.Ord_lists[0].pro_name != '' && this.Ord_lists[0].qua != '' ){
          
          axios.post(this.url_base + '/api/orders/orders_action.php',
            {Ord:this.Ord, Ord_lists:this.Ord_lists},
            { headers: {"Authorization" : `Bearer ${token}`}})
              .then(response => {
                  if (response.data.status) {
                    Swal.fire({
                      icon: 'success',
                      title: response.data.message,
                      showConfirmButton: false,
                      timer: 1500
                    });
                    this.$refs['m_close'].click();
                    this.get_Orders();  
                    this.Ord = {order_id:'', order_own:'',ord_app:'', order_date:'', ord_pay:'',ord_pay_name:'',comment:'',action:'insert'}
                    this.Ord_lists = [{pro_id:'', pro_name:'', unit_name:'', qua:''}]
                  }else{
                    Swal.fire({
                      icon: 'error',
                      title: response.data.message,
                      showConfirmButton: false,
                      timer: 1500
                    })
                  }
              })
              .catch(function (error) {
                  console.log(error);
              });
        }else{
          Swal.fire({
                      icon: 'error',
                      title: 'กรุณาตรวจสอบการป้อนข้อมูล',
                      showConfirmButton: false,
                      timer: 1500
                    });
        }

      },
      destroy_Order(order_id){
            Swal.fire({ 
                title: `Are you sure?  ${order_id}`,
                text: "You won't be able to revert this! ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {                  
                  this.Ord.action = 'delete';  
                  this.Ord.order_id = order_id;  
                  axios.post(this.url_base + '/api/orders/orders_action.php',{Ord:this.Ord},{ headers: {"Authorization" : `Bearer ${token}`}})
                    .then(response => {
                        if (response.data.status) {
                          Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_Orders(); 
                             
                        }else{
                          Swal.fire({
                            icon: 'error',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                          })
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                }
              });            
        },
        b_Order_close(){
          this.Ord = {order_id:'', order_own:'',ord_app:'', order_date:'', ord_pay:'',ord_pay_name:'',comment:'',action:'insert'}
          this.Ord_lists = [{pro_id:'', pro_name:'', unit_name:'', qua:''}]   
        },
        b_orl_plus(){
          this.Ord_lists.push({pro_id:'', pro_name:'', unit_name:'', qua:''})
        },
        b_orl_del(index){
          this.Ord_lists.pop()
        } ,
        b_pro_show(index){
          // console.log(index)
          this.q = ''
          this.get_Products()
          this.select_pro_index = index
        },
        ch_search_pro(){
          console.log(this.q)
          if(this.q.length > 0){
            axios.post(this.url_base + '/api/products/product_search.php',{q:this.q})
              .then(response => {
                  if (response.data.status){
                    this.products = response.data.respJSON;                    
                  }
              })
              .catch(function (error) {
                  console.log(error);
              });
          }else{
            this.get_Products()
          }
        },
        reset_search(){
          this.q = ''
        },
        handleBlurSearch(e) {
          this.get_Products()
          // console.log('blur', e.target.placeholder)
        },
        select_pro(pro_id,pro_name,unit_name,instock,min){
          this.Ord_lists[this.select_pro_index].pro_id = pro_id
          this.Ord_lists[this.select_pro_index].pro_name = pro_name
          this.Ord_lists[this.select_pro_index].unit_name = unit_name
          this.Ord_lists[this.select_pro_index].instock = instock
          this.Ord_lists[this.select_pro_index].min = min
          this.$refs['m2_close'].click();
          this.reset_search()
          // console.log(pro_id)
        },
        keyup_price(index){
          
        },
        keyup_qua(index){
          if(this.Ord_lists[index].qua > this.Ord_lists[index].instock){
            this.Ord_lists[index].qua = this.Ord_lists[index].instock
          }
          if(this.Ord_lists[index].qua < 0){
            this.Ord_lists[index].qua = 0
          }
          console.log(this.Ord_lists[index].qua)
          // this.Ord_lists[index].price = this.Ord_lists[index].price_one * this.Ord_lists[index].qua
          // this.count_price_total()
        },
        count_price_total(){    
          this.Ord.price_total = 0      
          for (let i = 0; i < this.Ord_lists.length; i++) {
            this.Ord.price_total = Number(this.Ord.price_total) + Number(this.Ord_lists[i].price)
            // console.log(this.Ord_lists.length + ' ' + parseInt(this.Ord_lists[i].price))
          }
        },
        formatCurrency(number) {
          number = parseFloat(number);
          return number.toFixed(2).replace(/./g, function(c, i, a) {
              return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
          });
        },
        formatCurrency0(number) {
          number = parseFloat(number);
          return number.toFixed(0).replace(/./g, function(c, i, a) {
              return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
          });
        },
        date_thai(day){
          var monthNamesThai = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม"];
          var dayNames = ["วันอาทิตย์ที่","วันจันทร์ที่","วันอังคารที่","วันพุทธที่","วันพฤหัสบดีที่","วันศุกร์ที่","วันเสาร์ที่"];
          var monthNamesEng = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
          var dayNamesEng = ['Sunday','Monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
          var d = new Date(day);
          return d.getDate() + ' ' + monthNamesThai[d.getMonth()] + "  " + (d.getFullYear() + 543)
        },
        test(num){
          console.log(num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
        },
        
        formatDate(date = new Date()) {
          const pad = (n) => n.toString().padStart(2, '0');

          const year   = date.getFullYear();
          const month  = pad(date.getMonth() + 1); // เดือนเริ่มจาก 0 ต้อง +1
          const day    = pad(date.getDate());
          const hour   = pad(date.getHours());
          const minute = pad(date.getMinutes());
          const second = pad(date.getSeconds());

          return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
        }

    }

  }).mount('#appOrder');
</script>
</body>
</html>
