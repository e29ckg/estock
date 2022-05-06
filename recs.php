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
                <h5 class="card-title">Recs</h5>
                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_Recs_insert()" ref="m_show">เพิ่มใบรับของ</button>                  
                              
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.rec_id}}</td>
                      <td>
                       {{data.rec_own}} {{data.rec_app}} {{data.str_id}}
                      </td>
                      <td>
                        <button @click="b_Recs_update(data.rec_id)" >Update</button>  
                        <button @click="destroy_Recs(data.rec_id)">Delete</button>  
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
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <!-- <form @submit.prevent="b_Recs_save()"> -->
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ร้านค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_Recs_close()">
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
                  <input type="text" class="form-control" v-model="Recs[0].date_receive" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <input type="e-mail" class="form-control" v-model="Recs[0].rec_app" required>
                </div>
              </div>
            </div> 

            <table class="table">
              <tr>
                <td>2</td>
                <td>2</td>
                <td>2</td>
              </tr>
              <tr>
                <td>2</td>
                <td>2</td>
                <td>2</td>
              </tr>
              <tr>
                <td>2</td>
                <td>2</td>
                <td>2</td>
              </tr>
            </table>
            <!-- <div class="row" v-for="rls,index in Rec_lists">
              <div class="col-sm-1">
                <div class="form-group">
                  {{index+1}}
                </div>
              </div>
              <div class="col-sm-3">                
                <div class="input-group mb-3">
                  <input type="text" class="form-control" v-model="rls.pro_id" hidden>
                  <input type="text" class="form-control" v-model="rls.pro_name" >
                  <div class="input-group-append">
                    <button class="input-group-text" data-toggle="modal" data-target="#exampleModal2" @click="b_pro_show(index)" ><i class="fas fa-check"></i></button>
                  </div>
                </div>                
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" v-model="rls.unit_name">
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                  <input type="text" class="form-control" v-model="rls.qua">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" v-model="rls.price_one">
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" v-model="rls.price">
                </div>
              </div>
              <div class="col-sm-1">
                <div class="form-group">
                  <button v-if="index +1  == Rec_lists.length && index > 0" @click="b_rls_del(index)">ลบ</button>
                </div>
              </div>
            </div>  -->
            <button class="btn btn-success" @click="b_rls_plus()">เพิ่ม</button>   
            
            {{Rec_lists}}

            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_Recs_close()">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal2" >modal2</button>                  
                  
          </div>
            <!-- {{Recs}} -->
            
          <!-- </form> -->
        </div>
      </div>
    </div>


    <!-- /**** */ -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal2" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog "  role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel2">เลือกร้านค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m2_close" >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <input type="text" v-model="q" @keyup="ch_search_pro" ref="search" placeholder="Search.">
          <div class="callout callout-danger" v-for="dp in products">
            <h5>
              <button class="btn btn-success" @click="select_pro(dp.pro_id,dp.pro_name)">เลือก</button>
              {{dp.pro_name}}
            </h5>
          </div>

          {{products}}
......
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  >Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
          </div>
            <!-- {{Recs}} -->
            
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
          date_receive:'',          
          str_id:'',          
          comment:'',          
          action:'insert'        
        }],
        Rec_lists:[],
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
      b_Recs_insert(){
        this.b_Recs_close();
      },  
      b_Recs_update(rec_id){
        this.$refs.m_show.click();
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
      b_Recs_save(){
        var jwt = localStorage.getItem("jwt");
        axios.post(url_base + 'api/recs/recs_action.php',{Recs:this.Recs},{ headers: {"Authorization" : `Bearer ${jwt}`}})
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
                  this.Recs = [{
                              rec_id:'',
                              rec_own:'',
                              rec_app:'',
                              str_id:'',
                              action:'insert'        
                            }];     
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
          this.Recs = [{
                        rec_id:'',
                        rec_own:'',
                        rec_app:'',
                        str_id:'',
                        action:'insert'        
                      }];     
        },
        b_rls_plus(){
          this.Rec_lists.push({pro_id:'', pro_name:'', unit_name:'', qua:'', price_one:'', price:''})
        },
        b_rls_del(index){
          this.Rec_lists.pop()
          console.log(index)
        } ,
        b_pro_show(index){
          console.log(index)
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
          this.q=''
        },
        handleBlurSearch(e) {
          this.q = ''
          this.get_Products()
          // console.log('blur', e.target.placeholder)
        },
        select_pro(pro_id,pro_name){
          this.Rec_lists[this.select_pro_index].pro_id = pro_id
          this.Rec_lists[this.select_pro_index].pro_name = pro_name
          this.$refs['m2_close'].click();
          console.log(pro_id)
        }
      },
  }).mount('#appRecs');
</script>
</body>
</html>
