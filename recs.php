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
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form @submit.prevent="b_Recs_save()">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ร้านค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_Recs_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <!-- <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อ</label>
                  <input type="text" class="form-control" v-model="Recs[0].rec_id" required>
                </div>
              </div>
            </div>    -->
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อร้านค้า</label>
                  <input type="text" class="form-control" v-model="Recs[0].rec_own" required>
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
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>โทรศัพท์</label>
                  <input type="text" class="form-control" v-model="Recs[0].str_phone" >
                </div>
              </div>
            </div>    
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_Recs_close()">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
          </div>
            <!-- {{Recs}} -->
            
          </form>
        </div>
      </div>
    </div>
    


  </div>
<!-- END APP -->
  <?php include "./layouts/footer.php";?>
</div>
<?php include "./layouts/footer2.php";?>
<script>
  var url_base = window.location.protocol + '//' + window.location.host + '/estock/';

  Vue.createApp({
    data() {
      return {
        datas:'',
        message: 'Hello Vue!',
        Recs:[{
          rec_id:'',
          rec_own:'',          
          rec_app:'',          
          str_id:'',          
          action:'insert'        
        }],
        
      }
    },
    mounted(){
      this.get_Recs();
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
        }        
      },
  }).mount('#appRecs');
</script>
</body>
</html>
