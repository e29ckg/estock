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
  <div class="content-wrapper" id="appUnits" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Units</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Units</li>
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
                <h5 class="card-title">Units</h5>
                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_unit_insert()" ref="m_show">เพิ่มหน่วยนับ</button>                  
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ชื่อหน่วยนับ</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.unit_id}}</td>
                      <td>
                          {{data.unit_name}}
                      </td>
                      <td>
                        <button class="btn btn-warning" @click="b_unit_update(data.unit_id)" >Update</button>  
                        <button class="btn btn-danger mx-2" @click="destroy_unit(data.unit_id)">Delete</button>  
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
          <form @submit.prevent="b_unit_save()">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_unit_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อหน่วยนับ</label>
                  <input type="text" class="form-control" v-model="unit.unit_name" required>
                </div>
              </div>
            </div>   
            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_unit_close()">Close</button>
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
  Vue.createApp({
    data() {
      return {
        url_base:'',
        datas:'',
        message: 'Hello Vue!',
        unit:{
          unit_id:'',
          unit_name:'',          
          action:'insert'        
        },
        
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host;
      this.get_Units();
    },
    methods: {      
      get_Units(){
        const token = getJWT();
        axios.post('api/units/read_units_all.php',{},{ headers: {"Authorization" : `Bearer ${token}`}})
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.datas = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_unit_insert(){
        this.b_unit_close();
      },  
      b_unit_update(unit_id){        
        this.$refs.m_show.click();
        const token = getJWT();
        axios.post('api/units/get_unit.php',{unit_id:unit_id},{ headers: {"Authorization" : `Bearer ${token}`}})
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.unit = {...response.data.respJSON,action : 'update'}; 
                    console.log(this.unit);                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },  
      b_unit_save(){
        const token = getJWT();
        axios.post('api/units/unit_save.php',{unit:this.unit},{ headers: {"Authorization" : `Bearer ${token}`}})
            .then(response => {
                // console.log(response.data);
                if (response.data.status ) {
                  Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_Units();  
                  this.unit = {unit_id:'', unit_name:'', action:'insert'};     
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
      },
      destroy_unit(unit_id){
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
                  const token = getJWT();
                  this.unit.action = 'delete';  
                  this.unit.unit_id = unit_id;  
                  axios.post('api/units/unit_save.php',{unit:this.unit},{ headers: {"Authorization" : `Bearer ${token}`}})
                    .then(response => {
                        // console.log(response.data);
                        if (response.data.status ) {
                          Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_Units(); 
                             
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
        b_unit_close(){
          this.unit = {
            unit_id:'',
            unit_name:'',
            action:'insert'        
          };     
        }        
      },
  }).mount('#appUnits');
</script>
</body>
</html>
