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
  <div class="content-wrapper" id="appReport" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
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
              <div class="card-header no-print">
                <h5 class="card-title">Report</h5>
                <div class="card-tools">
                  <div class="form-group">
                    <select class="form-control" id="search_year" v-model="year" @change="year_change">
                      <option v-for="sy in sel_year">{{sy}}</option>
                    </select>
                  </div>           
                </div>
              </div>
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <td colspan="6" class="text-center">
                        <h5>ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์<br>
                        รายละเอียดบัญชีวัสดุคงเหลือ<br>
                        {{text_head}}</h5>
                      </td>
                    </tr>
                    <tr class="text-center">
                      <th width="80px">ลำดับที่</th>
                      <th>รายการ</th>
                      <th>หน่วยนับ</th>
                      <th>จำนวนหน่วย</th>
                      <th>ราคาต่อหน่วย</th>
                      <th>จำนวนเงิน</th>
                    </tr>
                  </thead>
                  <tbody v-for="data,index in datas"> 
                     
                    <tr v-if=" data.lists != ''">
                      <td colspan="6" class="bg-gray"> {{data.cat_name}} </td>
                    </tr>
                    <tr class="text-center" v-for="dl,index in data.lists">
                      <td>{{dl.no}}</td>
                      <td class="text-left">{{dl.pro_name}}</td>
                      <td>{{dl.unit_name}}</td>
                      <td>{{formatCurrency0(dl.qua_for_ord)}}</td>
                      <td class="text-right">{{formatCurrency(dl.price_one)}}</td>
                      <td class="text-right">{{formatCurrency(dl.price)}}</td>
                    </tr>
                  
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5" class="text-right">รวม</td>
                      <td class="text-right bg-gray">{{formatCurrency(price_all)}}</td>
                    </tr>
                  </tfoot>
                </table>

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
  

  Vue.createApp({
    data() {
      return {
        url_base:'',
        datas:'',
        year:'',
        no:0,
        price_all:0 ,
        text_head:'',
        sel_year:[]  
        
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host + '/estock/';
      this.set_sel_year()
      this.get_Report();
    },
    methods: {   
      set_sel_year(){
        const d = new Date();
        year = d.getFullYear() + 542
        for (let i = 0; i < 3; i++) {
          this.sel_year.push(year + i)
        }
        this.year = d.getFullYear() + 543
      },  
      get_Report(){
        axios.post(this.url_base + 'api/report/get_report.php',{year:this.year})
            .then(response => {
                if (response.data.status) {
                    this.datas = response.data.respJSON;       
                    this.price_all = response.data.price_all;       
                    this.text_head = response.data.text_head;       
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      cal_price_one(index,qua,price){
        // this.datas[index].price = Number(qua) * Number(price)
        // return this.formatCurrency(this.datas[index].price)
      }, 
      no_index(){
        this.no = Number(this.no) + 1
        return this.formatCurrency0(this.no)
      },
      year_change(){
        this.get_Report()
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
  }).mount('#appReport');
</script>
</body>
</html>
