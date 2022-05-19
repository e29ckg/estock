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
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
   
      <?php include "./layouts/aside.php"; ?>
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="appProduct" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Products</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
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
                <h3 class="card-title">Products</h3>                
                <div class="card-tools">

                </div>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-sm-6">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_product_insert()" ref="m_show">เพิ่มสินค้า</button> 
                </div>
                  <div class="col-sm-6 ">
                    <div class="input-group mb-3 w-50 float-right">
                      <input type="text" class="form-control text-center" placeholder="ค้นหา." v-model="q" @keyup="search" ref="search">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                    </div>

                  </div>  
                </div>        

                <table class="table table-bordered">
                  <thead>
                    <tr class="text-center">
                      <th style="width: 10px">#</th>
                      <th>ภาพ</th>
                      <th>ชื่อสินค้า</th>
                      <th>ประเภทสินค้า/หน่วยนับ </th>
                      <th>คงเหลือ</th>
                      <th>สถานะ</th>
                      <th ></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                      <td class="text-center">{{index +1 }}</td>
                      <td class="text-center">
                        <!-- {{data.img}} -->
                        <a @click="b_pro_img(data.pro_id,index)"  data-toggle="modal" data-target="#myModal">
                          <img v-if="data.img" :src="'./uploads/'+ data.img" alt="data.img" class="float-left" height="60" >
                          <img v-else src="./uploads/none.png" alt="No-pic" class="float-left" height="60" >
                        </a>                        
                      </td>
                      <td class="text-left">
                        {{data.pro_name}}
                        <span class="badge bg-primary" @click="b_product_strock(data.pro_id)" data-toggle="modal" data-target="#myModalDetail">
                          <i class="fas fa-search mr-2"></i>detail
                        </span>                        
                      </td>
                      <td>
                          {{data.cat_name}}/
                          {{data.unit_name}}
                      </td>
                      <td class="text-center">{{formatCurrency0(data.instock)}}</td>
                      <td>
                        <span v-if="data.st == 1" class="badge bg-primary">ปกติ</span>
                        <span v-else class="badge bg-danger">ระงับ</span>
                      </td>
                      <td>
                        <button type="button" class="btn btn-block btn-warning btn-xs"  @click="b_product_update(data.pro_id)" >แก้ไข</button>                          
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
          <form @submit.prevent="b_product_save()">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_product_code()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อสินค้า</label>
                  <input type="text" class="form-control" v-model="product[0].pro_name" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>ประเภทสินค้า</label>
                  <select class="form-control" v-model="product[0].cat_name" required>
                    <option v-for="sc in sel_cats" :value="sc.cat_name">{{sc.cat_name}}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>หน่วยนับ</label>
                  <select class="form-control" v-model="product[0].unit_name" required>
                    <option v-for="sn in sel_units" :value="sn.unit_name">{{sn.unit_name}}</option>                    
                  </select>
                </div>
              </div>
            </div>

                <div class="form-group ">
                    <label>สถานทีเก็บ</label>
                    <textarea class="form-control" rows="3" placeholder="สถานที่..." v-model="product[0].locat"></textarea>
                </div>
                <div class="form-group ">
                    <label>รายละเอียด</label>
                    <textarea class="form-control" rows="3" placeholder="รายละเอียด" v-model="product[0].pro_detail"></textarea>
                </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                    <label>สั่งซื้อเมื่อต่ำกว่า</label>
                    <input type="number" class="form-control" v-model="product[0].lower">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>จำนวนที่ให้เบิกขั้นต่ำ</label>
                  <input type="number" class="form-control" v-model="product[0].min">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>
                สถานะ 
                <span v-if="product[0].st == 1" class="badge bg-primary">ปกติ</span>
                <span v-else class="badge bg-danger">ระงับ</span>
              </label>
    
              <select class="form-control" v-model="product[0].st" required>
                <option value="1">ใช้งาน</option>
                <option value="0">ไม่ใช้งาน</option>
              </select>
            </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_product_code()">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
          </div>
                
          </form>
        </div>
      </div>
    </div>
    

    <!-- Modal -->
    <div  class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{pro_img.title}}</h5>
            <button ref="m_img_upload" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div class="row mb-3" >
            <div class="col-sm-12" >
              <img class="img-fluid" :src="'./uploads/'+ pro_img.img" alt="Photo" v-if="pro_img.img">
            </div>
          </div>
                              
            <form @submit="onUpload">
            <!-- <input type="file" name="file" id="file" @change="previewFiles"> -->
              <input type="hidden" name="pro_id" :value="pro_img.id">
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="file" name="file" @change="onChangeInput()" ref="myFiles" :value="pro_img.val">
                  <label class="custom-file-label" for="exampleInputFile" >{{pro_img.label}}</label>
                </div>
                <div class="input-group-append">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal Detail -->
    <div  class="modal fade" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2"></h5>
            <button ref="m_product_stock" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>date</th>
                      <th>product</th>
                      <th>เลขเอกสาร</th>
                      <th>หน่วยนับ</th>
                      <th>ราคาต่อหน่วย</th>
                      <th>ยกมา</th>
                      <th>รับ</th>
                      <th>ออก</th>
                      <th>คงเหลือ</th>
                      <th>หมายเหตุ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="prst in pro_stock">
                      <td>{{prst.created_at}}</td>
                      <td>{{prst.pro_name}}</td>
                      <td>{{prst.rec_ord_id}}</td>
                      <td class="text-center">{{prst.unit_name}}</td>
                      <td class="text-right">{{formatCurrency(prst.price_one)}}</td>
                      <td class="text-center">{{formatCurrency0(prst.bf)}}</td>
                      <td class="text-center">{{formatCurrency0(prst.stck_in)}}</td>
                      <td class="text-center">{{formatCurrency0(prst.stck_out)}}</td>
                      <td class="text-center">{{formatCurrency0(prst.bal)}}</td>
                      <td>{{prst.comment}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
  Vue.createApp({
    data() {
      return {
        url_base:'',
        datas:'',
        message: 'Hello Vue!',
        product:[{pro_id:'', pro_name:'', pro_detail:'', cat_name:'', unit_name:'', locat:'',lower:1,min:1,st:'1',img:'',action:'insert'}],
        sel_cats:'',
        sel_units:'',
        pro_img:{
          id:'',
          title:'',
          label:'Choose file',
          img:'',
          val:''
        },
        q:'',
        pro_stock:''
      }
    },
    mounted(){
      this.get_products();
      this.url_base = window.location.protocol + '//' + window.location.host;
    },
    methods: {      
      get_products(){
        axios.post(this.url_base + '/estock/api/products/get_products.php')
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
      get_cats(){
        axios.post(this.url_base + '/estock/api/products/get_cats.php')
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.sel_cats = response.data.respJSON;
                    console.log(this.datas);                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_units(){
        axios.post(this.url_base + '/estock/api/products/get_units.php')
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.sel_units = response.data.respJSON;
                    console.log(this.sel_units);                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_product_insert(){
        this.b_product_code();
        this.get_cats();
        this.get_units();
      },  
      b_product_update(pro_id){
        this.$refs.m_show.click();
        axios.post(this.url_base + '/estock/api/products/get_product.php',{pro_id:pro_id})
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.product = response.data.respJSON;
                    this.product[0].action = 'update'; 
                    console.log(this.product);                   
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },  
      b_product_save(){
        var jwt = localStorage.getItem("jwt");
        axios.post(this.url_base + '/estock/api/products/product_save.php',{product:this.product},{ headers: {"Authorization" : 'Bearer ' + jwt}})
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
                  this.get_products();  
                  this.product = [{pro_id:'',pro_name:'',pro_detail:'',cat_name:'',nit_name:'',locat:'',lower:1,min:1,st:'1',action:'insert' }];     
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
      b_product_strock(pro_id){
        var jwt = localStorage.getItem("jwt");
        axios.post(this.url_base + '/estock/api/products/get_product_stock.php',{pro_id:pro_id})
            .then(response => {
                // console.log(response.data);
                if (response.data.status == 'success' ) {                  
                  this.pro_stock = response.data.respJSON   
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
      destroy_pro(pro_id){
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
                  this.product[0].action = 'delete';  
                  this.product[0].pro_id = pro_id;  
                  axios.post(this.url_base + '/estock/api/products/product_save.php',{product:this.product},{ headers: {"Authorization" : `Bearer ${jwt}`}})
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
                          this.get_products(); 
                             
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
        b_product_code(){
          this.product = [{pro_id:'', pro_name:'', pro_detail:'', cat_name:'', unit_name:'', locat:'', lower:1, min:1, st:'1', action:'insert'}];     
        },
        b_pro_img(pro_id,index){
          this.pro_img.id = pro_id;
          this.pro_img.img = this.datas[index].img;
          this.pro_img.title = this.datas[index].pro_id + ' ' + this.datas[index].pro_name;          
        },
        onChangeInput(event){
          this.onUpload()
        },
        onUpload(){
          var image = this.$refs.myFiles.files
          // console.log(this.$refs.myFiles.files[0].name);
          if (image.length > 0) {
            if(image[0].type == 'image/jpeg' || image[0].type =='image/png') {
              var formData = new FormData();
              // var imagefile = document.querySelector('#file');
              var imagefile = document.querySelector('#file');
              formData.append("sendimage", image[0]);
              formData.append("pro_id", this.pro_img.id);
              axios.post(
                this.url_base + '/estock/api/products/upload_img.php', 
                formData, 
                {headers:{'Content-Type': 'multipart/form-data'}
              })
                .then(response => {
                    if (response.data.status) {
                      swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: true,
                        timer: 1500
                      });
                      this.get_products();
                      this.pro_img.img = response.data.img;
                      // this.pro_img.label = '';
                    }else {
                        swal.fire({
                            icon: 'error',
                            title: response.data.message,
                            showConfirmButton: true,
                            timer: 1500
                        });
                    }
                })
            } else{
                swal.fire({
                    icon: 'error',
                    title: "ไฟล์ที่อัพโหลดต้องเป็นไฟล์ jpeg หรือ png เท่านั้น",
                    showConfirmButton: true,
                    timer: 1500
                });
              }
          }

        } ,
        test(){
          this.$refs.m_img_upload.click();
        },
        /** 
         * ต้นหา
         */
        search(){
          console.log(this.q)
          if(this.q.length > 0){
            axios.post(this.url_base + '/estock/api/products/product_search.php',{q:this.q})
              .then(response => {
                  if (response.data.status){
                    this.datas = response.data.respJSON;
                  }
              })
              .catch(function (error) {
                  console.log(error);
              });
          }else{
            this.get_products()
          }
        },
        reset_search(){
          this.q=''
        },
        handleBlurSearch(e) {
          this.q = ''
          this.get_products()
          // console.log('blur', e.target.placeholder)
        },
        getYM(dat){
            let MyDate = new Date(dat);
            let MyDateString;
            // MyDate.setDate(MyDate.getDate() + 20);
            MyDateString = MyDate.getFullYear() + '-' + ("0" + (MyDate.getMonth()+1)).slice(-2)
            return ("0" + MyDate.getDate()).slice(-2)+ '-' + ("0" + (MyDate.getMonth()+1)).slice(-2) + '-' + (MyDate.getFullYear() + 543)
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
        }
    },
  }).mount('#appProduct')
</script>
</body>
</html>
