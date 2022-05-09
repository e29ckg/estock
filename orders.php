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
                <h5 class="card-title">Order </h5>
                <div class="card-tools">
                  <!-- <button @click="test_action()">test</button> -->
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click.prevent="b_Order_insert()" ref="m_show">เพิ่มใบเบิกของ</button>                  
                              
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>วันที่เบิก</th>
                      <th>code</th>
                      <th>ผู้เบิก</th>
                      <th>สถานะ/วันที่ส่งมอบ</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                      <td>{{data.ord_date}}</td>
                      <td>{{data.ord_id}}</td>
                      <td>{{data.ord_own}}</td>
                      <td>
                        <span v-if="data.st == 0">รอการตรวจสอบ</span>
                        <span v-if="data.st == 1">อนุมัติแล้ว</span>
                      </td>
                      <td>{{data.st}}</td>
                      <td>
                        <button v-if="data.st == 0" data-toggle="modal" data-target="#exampleModal3" @click="b_Check(data.ord_id)">ตรวจสอบ</button>
                        <button v-else data-toggle="modal" data-target="#exampleModal3" @click="b_Check(data.ord_id)">รายละเอียด</button>
                      
                        <button @click.prevent="b_Order_update(data.ord_id)" v-if="data.st == 0" >Update</button>  
                        <button @click.prevent="destroy_Order(data.ord_id)" v-if="data.st == 0">Delete</button>  
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
            <h5 class="modal-title" id="exampleModalLabel">ผู้เบิก : {{Ord[0].ord_own}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click.prevent="b_Order_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <div class="row"> 
              <div class="col-sm-6">
                <div class="form-group">
                  <label>ผู้เบิก</label>
                  <input type="text" name="ord_own" id="ord_own" class="form-control" v-model="Ord[0].ord_own" disabled>
                  {{stores}}
                </div>
              </div>  
              <div class="col-sm-6">
                <div class="form-group">
                  <label>วันที่เบิก</label>
                  <input type="date" name="datepicker" id="datepicker" class="form-control" v-model="Ord[0].ord_date" required>
                </div>
              </div>
            </div>    -->
            <div class="row">   
              <!-- <div class="col-sm-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <input type="text" class="form-control" v-model="Ord[0].comment" required>
                </div> -->
              <!-- </div> -->
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
                     <input type="number" class="form-control text-center" :value="orl.instock" disabled>
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
            {{Ord[0].ord_own}} 
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
              <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m3_close">
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
                <small class="float-right">Date: {{Ord[0].ord_date}}</small>
              </h4>
              </div>
            </div>
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                ผู้เบิก
                <address>
                  <strong>{{Ord[0].ord_own}}</strong><br>
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
              <!-- To
              <address>
              <strong>...</strong><br>
              </address> -->
            </div>

            <div class="col-sm-4 invoice-col">
              <b>CODE #{{Ord[0].ord_id}}</b><br>
            </div>
          </div>

          <div class="row">
            <div class="col-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>หน่วยนับ</th>
                    <th>จำนวนที่มี</th>
                    <th>จำนวนที่ขอเบิก</th>
                    <th>หมายเหตุ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="orl,index in Ord_lists">
                    <td>{{index + 1 }}</td>
                    <td>{{orl.pro_name}}</td>
                    <td>{{orl.unit_name}}</td>
                    <td>{{orl.instock}}</td>
                    <td>{{orl.qua}}</td>
                    <td>
                      <button class="btn btn-danger" v-if="orl.qua > orl.instock || orl.qua == 0"><i class="fas fa-times"></i></button>
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
              <button type="button" class="btn btn-success float-right"  v-if="Ord[0].st == 0" @click="b_active()"><i class="far fa-credit-card"></i>
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
    


  </div>
<!-- END APP -->
  <?php include "./layouts/footer.php";?>
</div>
<?php include "./layouts/footer2.php";?>
<script>

</script>
<script>
 

  var url_base = window.location.protocol + '//' + window.location.host + '/estock/';

  Vue.createApp({
    data() {
      return {
        datas:'',
        q:'',
        message: 'Hello Vue!',
        stores:'',
        products:'',
        Ord:[{ord_id:'', ord_own:'',ord_app:'', ord_date:'', ord_pay:'',ord_pay_name:'',comment:'',action:'insert'}],
        Ord_lists:[{pro_id:'', pro_name:'', unit_name:'', qua:''}],
        select_pro_index:''
        
      }
    },
    mounted(){
      this.get_Orders()
      this.get_Products()
    },
    methods: {      
      get_Orders(){
        axios.post(url_base + 'api/orders/get_orders.php')
            .then(response => {
                if (response.data.status) {
                    this.datas = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Products(){
        axios.post(url_base + 'api/orders/get_products.php')
            .then(response => {
                if (response.data.status) {
                    this.products = response.data.respJSON;  
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Order(ord_id){
        axios.post(url_base + 'api/orders/get_order.php',{ord_id:ord_id})
            .then(response => {
                if (response.data.status) {
                  this.Ord = response.data.respJSON;       
                  this.Ord[0].action = 'update'                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Ord_list(ord_id){
        axios.post(url_base + 'api/orders/get_order_list.php',{ord_id:ord_id})
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
        var user_data = JSON.parse(localStorage.getItem("user_data"));
        this.Ord[0].ord_own = user_data.fullname
        console.log(user_data.fullname)
      },  
      b_Order_update(ord_id){        
        this.$refs['m_show'].click()
        this.get_Order(ord_id)
        this.get_Ord_list(ord_id)
      },
      b_Check(ord_id,str_id){
        this.get_Order(ord_id)
        this.get_Ord_list(ord_id)
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
                  this.Ord[0].action='active'
                  axios.post(url_base + 'api/orders/orders_action.php',{Ord:this.Ord, Ord_lists:this.Ord_lists},{ headers: {"Authorization" : `Bearer ${jwt}`}})
                    .then(response => {
                      if (response.data.status == 'success'){
                        Swal.fire({
                          icon: response.data.status,
                          title: response.data.massege,
                          showConfirmButton: false,
                          timer: 1500
                        });
                        this.get_Orders();
                        this.$refs['m3_close'].click();                    
                      }else{
                        Swal.fire({
                          icon: response.data.status,
                          title: response.data.massege,
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
        if(this.Ord[0].ord_own != '' && this.Ord[0].rec_date != '' && this.Ord_lists[0].pro_name != '' && this.Ord_lists[0].qua != '' ){
          var jwt = localStorage.getItem("jwt");
          axios.post(url_base + 'api/orders/orders_action.php',{Ord:this.Ord, Ord_lists:this.Ord_lists},{ headers: {"Authorization" : `Bearer ${jwt}`}})
              .then(response => {
                  if (response.data.status == 'success') {
                    Swal.fire({
                      icon: response.data.status,
                      title: response.data.massege,
                      showConfirmButton: false,
                      timer: 1500
                    });
                    this.$refs['m_close'].click();
                    this.get_Orders();  
                    this.Ord = [{ord_id:'', ord_own:'',ord_app:'', ord_date:'', ord_pay:'',ord_pay_name:'',comment:'',action:'insert'}]
                    this.Ord_lists = [{pro_id:'', pro_name:'', unit_name:'', qua:''}]
                  }else{
                    Swal.fire({
                      icon: response.data.status,
                      title: response.data.massege,
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
      destroy_Order(ord_id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                  var jwt = localStorage.getItem("jwt");
                  this.Ord[0].action = 'delete';  
                  this.Ord[0].ord_id = ord_id;  
                  axios.post(url_base + 'api/orders/orders_action.php',{Ord:this.Ord},{ headers: {"Authorization" : `Bearer ${jwt}`}})
                    .then(response => {
                        if (response.data.status == 'success') {
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.massege,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_Orders(); 
                             
                        }else{
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.massege,
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
          this.Ord = [{ord_id:'', ord_own:'',ord_app:'', ord_date:'', ord_pay:'',ord_pay_name:'',comment:'',action:'insert'}] 
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
            axios.post(url_base + 'api/products/product_search.php',{q:this.q})
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
          this.Ord[0].price_total = 0      
          for (let i = 0; i < this.Ord_lists.length; i++) {
            this.Ord[0].price_total = Number(this.Ord[0].price_total) + Number(this.Ord_lists[i].price)
            // console.log(this.Ord_lists.length + ' ' + parseInt(this.Ord_lists[i].price))
          }
        },
        
        test(num){
          console.log(num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
        }
      },
  }).mount('#appOrder');
</script>
</body>
</html>
