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
  <div class="content-wrapper" id="appRecs" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">รับของเข้า Store</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Recs</li>
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
                <h5 class="card-title">Recs </h5>
                <div class="card-tools">
                  <!-- <button @click="test_action()">test</button> -->
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click.prevent="b_Recs_insert()" ref="m_show">เพิ่มใบรับของ</button>                  
                              
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>วันที่รับ</th>
                      <th >รับจาก</th>
                      <th >ราคารวม</th>
                      <th >สถานะ</th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.rec_id}}</td>
                      <td>{{data.rec_date}}</td>
                      <td>{{data.str_name}}</td>
                      <td>{{data.price_total}}</td>
                      <td>
                        <!-- <span v-if="data.st == 0" class="badge bg-danger">รอการตรวจสอบ</span> -->
                        <span v-if="data.st == 1" class="badge bg-primary">อนุมัติแล้ว</span>
                        <button class="btn btn-block btn-danger btn-xs" v-if="data.st == 0" data-toggle="modal" data-target="#exampleModal3" @click="b_Check(data.rec_id,data.str_id,)">รอการตรวจสอบ</button>
                      </td>
                      <td>                        
                        <button class="btn btn-block btn-warning btn-xs" @click.prevent="b_Recs_update(data.rec_id)"v-if="data.st == 0" >Update</button>  
                        <button class="btn btn-block btn-danger btn-xs" @click.prevent="destroy_Recs(data.rec_id)" v-if="data.st == 0">Delete</button>  
                        <button class="btn btn-block btn-primary btn-xs" v-if="data.st == 1" data-toggle="modal" data-target="#exampleModal3" @click="b_Check(data.rec_id,data.str_id,)">รายละเอียด</button>
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
        <form @submit.prevent="b_Recs_save()">            
        <div class="modal-content">
         
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ใบรับของเข้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click.prevent="b_Recs_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"> 
            <div class="row"> 
              <div class="col-sm-6">
                <div class="form-group">
                <label>เลือกชื่อร้าน</label>
                <select class="form-control" v-model="Recs[0].str_id" required>
                  <option v-for="str in stores" :value="str.str_id">{{str.str_name}}</option>                    
                </select>
                <!-- {{stores}} -->
              </div>
              </div>  
              <div class="col-sm-6">
                <div class="form-group">
                  <label>วันที่รับ</label>
                  <input type="date" name="datepicker" id="datepicker" class="form-control" v-model="Recs[0].rec_date" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <input type="text" class="form-control" v-model="Recs[0].comment" required>
                </div>
              </div>
            </div> 
            <!-- {{Recs}} -->
            <table class="table">
              <thead class="text-center bg-lime">
                <tr>
                  <td >#</td>
                  <td width="30%">สินค้า</td>
                  <td>หน่วยนับ</td>
                  <td>จำนวน</td>
                  <td>ราคาต่อหน่วย</td>
                  <td></td>
                  <td></td>

                </tr>
              </thead>
              <tbody>

                <tr v-for="rls,index in Rec_lists">
                  <td>{{index + 1}}</td>
                  <td>
                    <div class="input-group">
                      <input type="text" class="form-control" v-model="rls.pro_id" hidden>
                      <input type="text" class="form-control" v-model="rls.pro_name" disabled>
                      <div class="input-group-append">
                        <button class="input-group-text" data-toggle="modal" data-target="#exampleModal2" @click.prevent="b_pro_show(index)" ><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </td>
                  <td>
                    <input type="text" class="form-control text-center" v-model="rls.unit_name" placeholder="หน่วยนับ" disabled>
                  </td>
                  <td>
                    <input type="number" class="form-control text-center" v-model="rls.qua" @keyup="keyup_qua(index)" @change="keyup_qua(index)" placeholder="จำนวน" v-if="rls.pro_name">
                    <input type="number" class="form-control text-center" v-model="rls.qua"  placeholder="จำนวน" v-else disabled>
                  </td>
                  <td> 
                    <input type="number" class="form-control text-right" v-model="rls.price_one" @keyup="keyup_price(index)"  @change="keyup_qua(index)" placeholder="ราตาต่อหน่วย" v-if="rls.pro_name">
                    <input type="number" class="form-control text-right" v-model="rls.price_one" placeholder="ราตาต่อหน่วย" v-else disabled>
                  </td>
                  <td>
                    <input type="text" class="form-control text-right" v-model="rls.price" placeholder="ราคารวม" disabled>
                  </td>
                  <td>
                    <button v-if="index +1  == Rec_lists.length && index > 0" @click.prevent="b_rls_del(index)" class="btn btn-danger btn-sm"><i class="fas fa-times"></i>ลบ</button></td>
                </tr>  
              </tbody>
              <tfoot class="">
                <tr>
                  <td colspan="5">
                    <button class="btn btn-success" @click.prevent="b_rls_plus()">
                      <i class="fas fa-plus"></i> เพิ่ม
                    </button>  
                  </td>
                  <td class="bg-green text-right">
                    <h5>
                      {{Recs[0].price_total}}
                    </h5>
                  </td>
                  <td></td>

                </tr>            
              </tfoot>
            </table>
            <!-- {{Rec_lists}} -->

            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click.prevent="b_Recs_close()">Close</button>
            <button type="submit" class="btn btn-primary" @click.prevent="b_Recs_save()">Save changes</button>     
          </div>
          </form>
            <!-- {{Recs}} -->            
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

            <div class="col-md-12 col-sm-12 col-12" v-for="dp in products"  @click.prevent="select_pro(dp.pro_id,dp.pro_name,dp.unit_name)">
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
            <!-- {{Recs}} -->            
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
            <!-- {{Recs}} -->
            <div class="row">
              <div class="col-12">
              <h4>
                <i class="fas fa-globe"></i> ใบรับของเข้า.
                <small class="float-right">Date: {{Recs[0].rec_date}}</small>
              </h4>
              </div>
            </div>
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                From
                <address>
                  <strong>{{Recs[0].str_name}}</strong><br>
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
              <!-- To
              <address>
              <strong>...</strong><br>
              </address> -->
            </div>

            <div class="col-sm-4 invoice-col">
              <b>CODE #{{Recs[0].rec_id}}</b><br>
              <b>ผู้บันทึก:</b> {{Recs[0].rec_own}}
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
                    <th>จำนวน</th>
                    <th>ราคาต่อหน่วย</th>
                    <th>ราคา</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="rls,index in Rec_lists">
                    <td>{{index + 1 }}</td>
                    <td>{{rls.pro_name}}</td>
                    <td>{{rls.unit_name}}</td>
                    <td>{{rls.qua}}</td>
                    <td>{{rls.price_one}}</td>
                    <td class="text-right">{{rls.price}}</td>
                  </tr>            
                  <tr>
                    <td colspan="5"></td>
                    <td class="bg-gray text-right">{{Recs[0].price_total}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row no-print">
            <div class="col-12">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"  >Close</button>
              <button type="button" class="btn btn-success float-right"  v-if="Recs[0].st == 0" @click="b_active()"><i class="far fa-credit-card"></i>
                อนุมัติ
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
        Recs:[{
          rec_id:'',
          rec_own:'',          
          rec_app:'',          
          rec_date:'',          
          str_id:'',          
          price_total:'',          
          comment:'',          
          action:'insert'        
        }],
        Rec_lists:[{pro_id:'', pro_name:'', unit_name:'', qua:'', price_one:'', price:0}],
        select_pro_index:''
        
      }
    },
    mounted(){
      this.get_Recs()
      this.get_Stores()
      this.get_Products()
    },
    methods: {      
      get_Recs(){
        axios.post(url_base + 'api/recs/get_recs.php')
            .then(response => {
                if (response.data.status) {
                    this.datas = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Stores(){
        axios.post(url_base + 'api/store/get_stores.php')
            .then(response => {
                if (response.data.status) {
                    this.stores = response.data.respJSON; 
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Store(str_id){
        axios.post(url_base + 'api/store/get_store.php',{str_id:str_id})
            .then(response => {
                if (response.data.status) {
                  // console.log(response.data.respJSON)
                    this.Recs[0].str_name = response.data.respJSON[0].str_name; 
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Products(){
        axios.post(url_base + 'api/products/get_products.php')
            .then(response => {
                if (response.data.status) {
                    this.products = response.data.respJSON;  
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_rec(rec_id){
        axios.post(url_base + 'api/recs/get_rec.php',{rec_id:rec_id})
            .then(response => {
                if (response.data.status) {
                  this.Recs = response.data.respJSON;
                  this.Recs[0].action = 'update';              
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_rec_list(rec_id){
        axios.post(url_base + 'api/recs/get_rec_list.php',{rec_id:rec_id})
            .then(response => {
                if (response.data.status) {
                  this.Rec_lists = response.data.respJSON;    
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_Recs_insert(){
        this.b_Recs_close();
      },  
      b_Recs_update(rec_id){
        this.$refs['m_show'].click();
        this.get_rec(rec_id)
        this.get_rec_list(rec_id)
      },
      async b_Check(rec_id,str_id){
        await this.get_rec(rec_id)
        await this.get_rec_list(rec_id)
        await this.get_Store(str_id)
        console.log(str_id)
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
            this.Recs[0].action='active'
            axios.post(url_base + 'api/recs/recs_action.php',{Recs:this.Recs, Rec_lists:this.Rec_lists},{ headers: {"Authorization" : `Bearer ${jwt}`}})
              .then(response => {
                  if (response.data.status == 'success'){
                    Swal.fire({
                      icon: response.data.status,
                      title: response.data.massege,
                      showConfirmButton: false,
                      timer: 1500
                    });
                    this.get_Recs();
                    this.$refs['m3_close'].click();                    
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

      b_Recs_save(){

        if(this.Recs[0].str_id != '' && this.Recs[0].rec_date != '' && this.Rec_lists[0].pro_name != ''){
          var jwt = localStorage.getItem("jwt");
          axios.post(url_base + 'api/recs/recs_action.php',{Recs:this.Recs, Rec_lists:this.Rec_lists},{ headers: {"Authorization" : `Bearer ${jwt}`}})
              .then(response => {
                  if (response.data.status == 'success') {
                    Swal.fire({
                      icon: response.data.status,
                      title: response.data.massege,
                      showConfirmButton: false,
                      timer: 1500
                    });
                    this.$refs['m_close'].click();
                    this.get_Recs();  
                    this.Recs = [{rec_id:'', rec_own:'', rec_app:'', str_id:'', action:'insert'}]
                    this.Rec_lists = [{pro_id:'', pro_name:'', unit_name:'', qua:'', price_one:'', price:0}]
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
      destroy_Recs(rec_id){
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
                  this.Recs[0].action = 'delete';  
                  this.Recs[0].rec_id = rec_id;  
                  axios.post(url_base + 'api/recs/recs_action.php',{Recs:this.Recs},{ headers: {"Authorization" : `Bearer ${jwt}`}})
                    .then(response => {
                        if (response.data.status == 'success') {
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.massege,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_Recs(); 
                             
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
        b_Recs_close(){
          this.Recs = [{rec_id:'',rec_own:'',rec_app:'', rec_date:'',str_id:'',price_total:0, comment:'',action:'insert'}]  
          this.Rec_lists = [{pro_id:'', pro_name:'', unit_name:'', qua:'', price_one:'', price:0}]   
        },
        b_rls_plus(){
          this.Rec_lists.push({pro_id:'', pro_name:'', unit_name:'', qua:'', price_one:'', price:0})
        },
        b_rls_del(index){
          this.Rec_lists.pop()
          this.count_price_total()
          // console.log(index)
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
        select_pro(pro_id,pro_name,unit_name){
          this.Rec_lists[this.select_pro_index].pro_id = pro_id
          this.Rec_lists[this.select_pro_index].pro_name = pro_name
          this.Rec_lists[this.select_pro_index].unit_name = unit_name
          this.$refs['m2_close'].click();
          this.reset_search()
          // console.log(pro_id)
        },
        keyup_price(index){
          this.Rec_lists[index].price = this.Rec_lists[index].price_one * this.Rec_lists[index].qua
          this.count_price_total()
        },
        keyup_qua(index){
          this.Rec_lists[index].price = this.Rec_lists[index].price_one * this.Rec_lists[index].qua
          this.count_price_total()
        },
        count_price_total(){    
          this.Recs[0].price_total = 0      
          for (let i = 0; i < this.Rec_lists.length; i++) {
            this.Recs[0].price_total = Number(this.Recs[0].price_total) + Number(this.Rec_lists[i].price)
            // console.log(this.Rec_lists.length + ' ' + parseInt(this.Rec_lists[i].price))
          }
        },
        
        test(num){
          console.log(num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
        }
      },
  }).mount('#appRecs');
</script>
</body>
</html>
