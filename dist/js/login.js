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
}