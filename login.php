<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Stock v2 | Log in</title>

  <link rel="icon" href="./dist/img/favicon.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="./node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="./node_modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="./node_modules/admin-lte/dist/css/adminlte.min.css?v=3.2.0">
</head>
<body class="hold-transition login-page">

  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>E-Stock</b></a>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form onsubmit="return login()" method="post" action="#">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username or Email" id="username" name="username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required autocomplete="new-password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <!-- ✅ Remember Me -->
          <div class="row mb-3">
            <div class="col-6">
              <div class="icheck-primary">
                <input type="checkbox" id="rememberMe">
                <label for="rememberMe">Remember Me</label>
              </div>
            </div>
            <div class="col-6 text-right">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>

        <p class="mb-1 mt-3">
          <!-- <a href="forgot-password.html">I forgot my password</a> -->
        </p>
        <p class="mb-0">
          <a href="register.html" class="text-center">Register a new membership</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
  <script src="./node_modules/admin-lte/plugins/jquery/jquery.min.js"></script>
  <script src="./node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./node_modules/admin-lte/dist/js/adminlte.min.js?v=3.2.0"></script>
  <script>
  const url_base = window.location.protocol + '//' + window.location.host;

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const submitBtn = form.querySelector("button[type=submit]");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    await handleLogin(form, submitBtn);
  });
});

async function handleLogin(form, submitBtn) {
  const username = form.username.value.trim();
  const password = form.password.value.trim();
  const rememberMe = document.getElementById("rememberMe").checked;

  if (!username || !password) {
    return showAlert("กรุณากรอกชื่อผู้ใช้และรหัสผ่าน", "warning");
  }

  submitBtn.disabled = true;
  submitBtn.innerText = "Signing in...";

  try {
    const data = await loginRequest(username, password);

    if (data.status === "success") {
      await Swal.fire({
        icon: "success",
        title: data.message,
        showConfirmButton: false,
        timer: 1200
      });

      const storage = rememberMe ? localStorage : sessionStorage;
      storage.setItem("jwt", data.jwt);
      storage.setItem("user_data", JSON.stringify(data.user_data));

      window.location.href = "./index.php";
    } else {
      showAlert(data.message || "เข้าสู่ระบบไม่สำเร็จ", "error");
    }
  } catch (err) {
    console.error("Login error:", err);
    showAlert(err.message || "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์", "error");
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerText = "Sign In";
  }
}

async function loginRequest(username, password) {
  const response = await fetch(`${url_base}/api/auth/login.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json;charset=UTF-8" },
    body: JSON.stringify({ username, password })
  });

  const data = await response.json().catch(() => null);

  if (!response.ok) {
    throw new Error(data?.message || `HTTP error! status: ${response.status}`);
  }

  return data;
}

function showAlert(message, icon = "info") {
  return Swal.fire({
    text: message,
    icon,
    confirmButtonText: "OK"
  });
}</script>
</body>
</html>