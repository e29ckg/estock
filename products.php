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
                        <a @click="b_product_update(data.pro_id)" >
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
    
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="b_product_save()">
            <div class="modal-header">
              <h5 class="modal-title">แก้ไขสินค้า</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_product_close()">
                      <span aria-hidden="true">&times;</span>
              </button>
              
            </div>

            <div class="modal-body">
              <div class="form-group text-center">
                <!-- แสดงรูป -->
                <img :src="previewImage" 
                    class="img-thumbnail" 
                    style="width:150px; height:150px; cursor:pointer;" 
                    @click="$refs.fileInput.click()">

                <!-- input file ซ่อน -->
                <input type="file" 
                      ref="fileInput" 
                      style="display:none" 
                      accept="image/*" 
                      @change="onFileChange">
              </div>

              <!-- ชื่อสินค้า -->
              <div class="form-group mb-3">
                <label>ชื่อสินค้า</label>
                <input type="text" class="form-control" v-model="product.pro_name" required>
              </div>

              <!-- ประเภทสินค้า + หน่วย -->
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mb-3">
                    <label>ประเภทสินค้า</label>
                    <select class="form-control" v-model="product.cat_id" required>
                      <option v-for="sc in sel_cats" :value="sc.cat_id">{{ sc.cat_name }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group mb-3">
                    <label>หน่วยนับ</label>
                    <select class="form-control" v-model="product.unit_id" required>
                      <option v-for="sn in sel_units" :value="sn.unit_id">{{ sn.unit_name }}</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- สถานที่เก็บ -->
              <div class="form-group mb-3">
                <label>สถานที่เก็บ</label>
                <textarea class="form-control" rows="2" v-model="product.locat"></textarea>
              </div>

              <!-- รายละเอียด -->
              <div class="form-group mb-3">
                <label>รายละเอียด</label>
                <textarea class="form-control" rows="2" v-model="product.pro_detail"></textarea>
              </div>

              <!-- lower / min -->
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mb-3">
                    <label>สั่งซื้อเมื่อต่ำกว่า</label>
                    <input type="number" class="form-control" v-model="product.lower">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group mb-3">
                    <label>จำนวนที่ให้เบิกขั้นต่ำ</label>
                    <input type="number" class="form-control" v-model="product.min">
                  </div>
                </div>
              </div>

              <!-- สถานะ -->
              <div class="form-group mb-3">
                <label>
                  สถานะ
                  <span v-if="product.st == 1" class="badge bg-primary">ปกติ</span>
                  <span v-else class="badge bg-danger">ระงับ</span>
                </label>
                <select class="form-control" v-model="product.st" required>
                  <option value="1">ใช้งาน</option>
                  <option value="0">ไม่ใช้งาน</option>
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="b_product_close()">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
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
                      <td :data-date="prst.created_at">{{date_thai(prst.to_do_date)}}</td>
                      <td>{{prst.pro_name}}</td>
                      <td>{{prst.rec_order_id}}</td>
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
        url_base: '.', // ตั้งค่า base path
        datas: [],        // ข้อมูลที่แสดงในตาราง (filtered)
        all_datas: [],    // เก็บข้อมูลต้นฉบับทั้งหมด
        q: '',             // คำค้นหา
        message: 'Hello Vue!',
        product: this.defaultProduct(),
        sel_cats: [],
        sel_units: [],
        pro_img: {
          id: '',
          title: '',
          label: 'Choose file',
          img: '',
          val: ''
        },
        pro_stock: '',
        previewImage: './uploads/none.png', // รูป default

      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host;
      this.get_products();
      this.get_cats();
      this.get_units();
    },
    methods: {     
      defaultProduct() {
        return {
          pro_id: '',
          pro_name: '',
          pro_detail: '',
          cat_id: '',     // ใช้ id แทน name จะ save ง่ายกว่า
          unit_id: '',
          locat: '',
          lower: 1,
          min: 1,
          st: '1',
          img: '',
          action: 'insert'
        }
      },
 
      get_products() {
        const token = getJWT(); // ดึง JWT จาก localStorage/sessionStorage

        axios.get('./api/products/get_products.php', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        .then(response => {
          if (response.data.status) {
            this.all_datas = response.data.respJSON;
            this.datas = this.all_datas; // เริ่มต้นแสดงทั้งหมด
          } else {
            console.warn("API returned error:", response.data.message);
          }
        })
        .catch(error => {
          console.error("Error loading products:", error);
          this.handleAuthError(error); // ฟังก์ชันที่คุณเขียนไว้ เช่น clear token + redirect
        });
      },
      get_cats() {
        const token = getJWT();

        axios.get(this.url_base + '/api/products/get_cats.php', {
          headers: { Authorization: `Bearer ${token}` }
        })
        .then(response => {
          if (response.data.status) {
            this.sel_cats = response.data.respJSON;
            console.log(this.sel_cats);
          } else {
            console.warn("get_cats error:", response.data.message);
          }
        })
        .catch(error => {
          console.error("get_cats failed:", error);
          this.handleAuthError(error);
        });
      },

      get_units() {
        const token = getJWT();

        axios.get(this.url_base + '/api/products/get_units.php', {
          headers: { Authorization: `Bearer ${token}` }
        })
        .then(response => {
          if (response.data.status) {
            this.sel_units = response.data.respJSON;
            console.log(this.sel_units);
          } else {
            console.warn("get_units error:", response.data.message);
          }
        })
        .catch(error => {
          console.error("get_units failed:", error);
          this.handleAuthError(error);
        });
      },

      b_product_close(){
        this.product = this.defaultProduct();
        this.$refs.m_close.click();
      },
      b_product_insert(){
        //this.product = this.defaultProduct();
        //this.get_cats();
        //this.get_units();
      },  
      b_product_update(pro_id) {
        const token = getJWT();

        axios.post(this.url_base + '/api/products/get_product.php',
          { pro_id: pro_id },
          { headers: { Authorization: `Bearer ${token}` } }
        )
        .then(response => {
          console.log("API response:", response.data);

          if (response.data.status && response.data.respJSON.length > 0) {
            this.product = { ...response.data.respJSON[0], action: 'update' };

            if (this.product.img) {
              this.previewImage = this.url_base + '/uploads/' + this.product.img;
            } else {
              this.previewImage = this.url_base + '/uploads/none.png';
            }

            this.$refs.m_show.click();
            console.log("Loaded product:", this.product);
          } else {
            console.warn("Product not found or API error");
          }
        })
        .catch(error => {
          console.error("Error loading product:", error);
          this.handleAuthError(error);
        });
      },
      onFileChange(event) {
  const file = event.target.files[0];
  if (!file) return;

  // ✅ ตรวจสอบว่าเป็นไฟล์รูปภาพ
  if (!file.type.startsWith("image/")) {
    Swal.fire({ icon: 'error', title: 'กรุณาเลือกไฟล์รูปภาพเท่านั้น', timer: 1500 });
    return;
  }

  // ✅ ตรวจสอบขนาดไฟล์ไม่เกิน 2 MB
  if (file.size > 2 * 1024 * 1024) {
    Swal.fire({ icon: 'error', title: 'ไฟล์ต้องมีขนาดไม่เกิน 2 MB', timer: 1500 });
    return;
  }

  // ✅ เก็บไฟล์ไว้ใน product.img สำหรับส่งไป backend
  this.product.img = file;

  // ✅ อ่านไฟล์เป็น DataURL เพื่อแสดง preview
  const reader = new FileReader();
  reader.onload = e => {
    this.previewImage = e.target.result; // ใช้ใน <img :src="previewImage">
  };
  reader.readAsDataURL(file);
},

       // ฟังก์ชันบันทึก (upload ไป backend)
      b_product_save() {
        const token = getJWT();
        let formData = new FormData();

        // แนบข้อมูลสินค้า
        formData.append("pro_id", this.product.pro_id);
        formData.append("pro_name", this.product.pro_name);
        formData.append("pro_detail", this.product.pro_detail);
        formData.append("cat_id", this.product.cat_id);
        formData.append("unit_id", this.product.unit_id);
        formData.append("locat", this.product.locat);
        formData.append("lower", this.product.lower);
        formData.append("min", this.product.min);
        formData.append("st", this.product.st);
        formData.append("action", this.product.action);

        // แนบไฟล์รูป (ถ้ามี)
        if (this.product.img instanceof File) {
          formData.append("img", this.product.img);
        }

        axios.post(this.url_base + '/api/products/product_save.php',
          formData,
          {
            headers: {
              "Authorization": "Bearer " + token,
              "Content-Type": "multipart/form-data"
            }
          }
        )
        .then(response => {
          if (response.data.status) {
            Swal.fire({ icon: 'success', title: response.data.message, timer: 1500 });
            this.$refs['m_close'].click();
            this.get_products();
            this.product = this.defaultProduct(); // reset object
          } else {
            Swal.fire({ icon: 'error', title: response.data.message, timer: 1500 });
          }
        })
        .catch(error => {
          console.error("Save product error:", error);
        });
      },
      b_product_strock(pro_id) {
        const token = getJWT();
        axios.post(this.url_base + '/api/products/get_product_stock.php', { pro_id: pro_id },{ headers: { Authorization: `Bearer ${token}` } })
          .then(response => {
            console.log("API response:", response.data);

            if (response.data.status) {   // <-- เช็ค boolean แทน 'success'
              this.pro_stock = response.data.respJSON;
            } else {
              Swal.fire({
                icon: 'error',
                title: response.data.message || "ไม่พบข้อมูลสต็อก",
                showConfirmButton: false,
                timer: 1500
              });
            }
          })
          .catch(error => {
            console.error("Error loading stock:", error);
            Swal.fire({
              icon: 'error',
              title: "เกิดข้อผิดพลาดในการโหลดข้อมูล",
              showConfirmButton: false,
              timer: 1500
            });
          });
      },
      test(){
          this.$refs.m_img_upload.click();
      },
      search() {
        const keyword = this.q.toLowerCase().trim();
          if (keyword === '') {
            this.datas = this.all_datas; // ถ้าไม่พิมพ์อะไร แสดงทั้งหมด
          } else {
            this.datas = this.all_datas.filter(item => {
              return (
                (item.pro_name && item.pro_name.toLowerCase().includes(keyword)) ||
                (item.cat_name && item.cat_name.toLowerCase().includes(keyword)) ||
                (item.unit_name && item.unit_name.toLowerCase().includes(keyword)) ||
                (item.locat && item.locat.toLowerCase().includes(keyword))
              );
            });
          }
        },
        reset_search(){
          this.q=''
        },
        
        date_thai(day){
          var monthNamesThai = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม"];
          var dayNames = ["วันอาทิตย์ที่","วันจันทร์ที่","วันอังคารที่","วันพุทธที่","วันพฤหัสบดีที่","วันศุกร์ที่","วันเสาร์ที่"];
          var monthNamesEng = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
          var dayNamesEng = ['Sunday','Monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
          var d = new Date(day);
          return d.getDate() + ' ' + monthNamesThai[d.getMonth()] + "  " + (d.getFullYear() + 543)
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
