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
  <div class="content-wrapper" id="appCatalogs" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Catalogs</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Catalogs</li>
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
                <h5 class="card-title">Catalogs</h5>
                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_catalog_insert()" ref="m_show">เพิ่มประเภท</button>                  
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ชื่อประเภท</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.cat_id}}</td>
                      <td>
                          {{data.cat_name}}
                      </td>
                      <td>
                        <button @click="b_catalog_update(data.cat_id)" >Update</button>  
                        <button @click="destroy_cat(data.cat_id)">Delete</button>  
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
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form @submit.prevent="b_catalog_save()">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_catalog_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อประเภทสินค้า</label>
                  <input type="text" class="form-control" v-model="catalog[0].cat_name" required>
                </div>
              </div>
            </div>   
            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_catalog_close()">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
          </div>
            <!-- {{catatlog}} -->
            
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
  var url_base = window.location.protocol + '//' + window.location.host;

  Vue.createApp({
    data() {
      return {
        datas:'',
        message: 'Hello Vue!',
        catalog:[{
          cat_id:'',
          cat_name:'',          
          action:'insert'        
        }],
        
      }
    },
    mounted(){
      this.get_catalogs();
    },
    methods: {      
      get_catalogs(){
        axios.post(url_base + '/estock/api/catalogs/read_catalogs_all.php')
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.datas = response.data.respJSON;
                    // console.log(this.datas);                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_catalog_insert(){
        this.b_catalog_close();
      },  
      b_catalog_update(cat_id){
        this.$refs.m_show.click();
        axios.post(url_base + '/estock/api/catalogs/get_catalog.php',{cat_id:cat_id})
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.catalog = response.data.respJSON;
                    this.catalog[0].action = 'update'; 
                    console.log(this.catalog);                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },  
      b_catalog_save(){
        var jwt = localStorage.getItem("jwt");
        axios.post(url_base + '/estock/api/catalogs/catalog_save.php',{catalog:this.catalog},{ headers: {"Authorization" : `Bearer ${jwt}`}})
            .then(response => {
                // console.log(response.data);
                if (response.data.status ) {
                  Swal.fire({
                    // position: 'top-end',
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_catalogs();  
                  this.catalog = [{
                              cat_id:'',
                              cat_name:'',
                              action:'insert'        
                            }];     
                }else{
                  Swal.fire({
                    // position: 'top-end',
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
      },
      destroy_cat(cat_id){
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
                  this.catalog[0].action = 'delete';  
                  this.catalog[0].cat_id = cat_id;  
                  axios.post(url_base + '/estock/api/catalogs/catalog_save.php',{catalog:this.catalog},{ headers: {"Authorization" : `Bearer ${jwt}`}})
                    .then(response => {
                        // console.log(response.data);
                        if (response.data.status ) {
                          Swal.fire({
                            // position: 'top-end',
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_catalogs(); 
                             
                        }else{
                          Swal.fire({
                            // position: 'top-end',
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
        b_catalog_close(){
          this.catalog = [{
                              cat_id:'',
                              cat_name:'',
                              action:'insert'        
                            }];     
        }        
      },
  }).mount('#appCatalogs');
</script>
</body>
</html>
