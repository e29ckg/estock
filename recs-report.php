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
<!-- <div class="wrapper" > -->

  <!-- Content Wrapper. Contains page content -->
  <div class="wrapper" id="appRecs" v-cloak>   

    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <td colspan="6" class="text-center"><b> <h5>ใบรับของเข้า</h5> </b></td>
            </tr>
            <tr>
              <td colspan="3" >
                <strong>From </strong>{{rec.str_name}}<br>
                <strong> {{rec.str_detail}}</strong> PHONE: </strong>{{rec.str_phone}}<br>
              </td>
              <td colspan="3" class="text-right" >
                <strong>CODE : </strong>{{rec.rec_id}}<br>
                <strong>DATE : </strong>{{date_thai(rec.rec_date)}} <br>
                ผู้บันทึกข้อมูล  : {{rec.rec_own}}  <br>ผู้อนุมัติ  : {{rec.rec_app}}<br>
              </td>
            </tr>
            <tr class="text-center">
              <th>#</th>
              <th>Product</th>
              <th>หน่วยนับ</th>
              <th>จำนวน</th>
              <th>ราคาต่อหน่วย</th>
              <th>ราคา</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data,index in datas.rec_lists" class="text-center">
              <td>{{index + 1 }}</td>
              <td class="text-left">{{data.pro_name}}</td>
              <td>{{data.unit_name}}</td>
              <td>{{data.qua}}</td>
              <td class="text-right">{{formatCurrency(data.price_one)}}</td>
              <td class="text-right">{{formatCurrency(data.price)}}</td>
            </tr>            
            <tr>
              <td colspan="5"></td>
              <td class="bg-gray text-right"> {{formatCurrency(summary.price_all)}} </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="no-print text-center mt-5">
      <button rel="noopener" class="btn btn-default" onclick="window.print()">
        <i class="fas fa-print"></i> Print
      </button>
    </div>
    

  </div>
<!-- END APP -->
 
<!-- </div> -->
<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="./node_modules/admin-lte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./node_modules/admin-lte/dist/js/adminlte.min.js"></script>
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="./node_modules/admin-lte/plugins/select2/js/select2.min.js"></script>
<!-- <script src="./node_modules/vue/dist/vue.min.js"></script> -->

<script src="./dist/js/vue.global.js"></script>
<script src="./node_modules/axios/dist/axios.min.js"></script>

<script>

</script><script>
Vue.createApp({
  data() {
    return {
      datas: [null],
      rec: {},
      summary: {}
    }
  },
  mounted() {
    // ✅ อ่าน rec_id จาก query string
    const urlParams = new URLSearchParams(window.location.search);
    const rec_id = urlParams.get("rec_id");

    if (rec_id) {
      // ✅ ดึงข้อมูลจาก API
      axios.get(`./api/recs/rec_report.php?rec_id=${rec_id}`)
        .then(res => {
          if (res.data.status) {
            this.datas = res.data;
            this.rec = res.data.rec;
            this.summary = {...res.data.summary};
          } else {
            console.error("ไม่พบข้อมูล", res.data.message);
          }
        })
        .catch(err => console.error("API error:", err));
    }
  },
  methods: {
    formatCurrency(number) {
      number = parseFloat(number);
      return number.toFixed(2).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
      });
    },
    date_thai(day) {
      const monthNamesThai = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
      const d = new Date(day);
      return d.getDate() + ' ' + monthNamesThai[d.getMonth()] + " " + (d.getFullYear() + 543);
    }
  }
}).mount('#appRecs');
</script>
</body>
</html>
