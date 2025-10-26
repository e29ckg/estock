/**
 * auth.js
 * Utility สำหรับจัดการ JWT, Refresh Token และ user_data
 */

// ✅ ดึง user_data
function getUserData() {
  try {
    const data =
      localStorage.getItem("user_data") ||
      sessionStorage.getItem("user_data");
    return data ? JSON.parse(data) : null;
  } catch (e) {
    console.error("Error parsing user_data:", e);
    return null;
  }
}

// ✅ ดึง Access Token
function getJWT() {
  return (
    localStorage.getItem("jwt") ||
    sessionStorage.getItem("jwt") ||
    null
  );
}

// ✅ ดึง Refresh Token
function getRefreshToken() {
  return (
    localStorage.getItem("refresh_jwt") ||
    sessionStorage.getItem("refresh_jwt") ||
    null
  );
}

// ✅ เก็บ token และ user_data
function saveTokens(jwt, refresh_jwt, expireAt, refreshExpAt, user_data) {
  if (jwt) localStorage.setItem("jwt", jwt);
  if (refresh_jwt) localStorage.setItem("refresh_jwt", refresh_jwt);
  if (expireAt) localStorage.setItem("expireAt", expireAt);
  if (refreshExpAt) localStorage.setItem("refreshExpAt", refreshExpAt);
  if (user_data) localStorage.setItem("user_data", JSON.stringify(user_data));
}

// ✅ ตรวจสอบการ login
function requireAuth() {
  const token = getJWT();
  if (!token) {
    window.location.href = "login.php";
  }
}

// ✅ ลบข้อมูล auth ทั้งหมด
function clearAuth() {
  ["jwt", "refresh_jwt", "expireAt", "refreshExpAt", "user_data"].forEach(k => {
    localStorage.removeItem(k);
    sessionStorage.removeItem(k);
  });
}

// ✅ ตรวจสอบว่า access token หมดอายุหรือยัง
function isTokenExpired() {
  const expireAt = localStorage.getItem("expireAt");
  if (!expireAt) return true;
  return Date.now() >= parseInt(expireAt) * 1000;
}

// ✅ ตรวจสอบว่า refresh token หมดอายุหรือยัง
function isRefreshExpired() {
  const refreshExpAt = localStorage.getItem("refreshExpAt");
  if (!refreshExpAt) return true;
  return Date.now() >= parseInt(refreshExpAt) * 1000;
}

// ✅ ใช้กับปุ่ม logout
document.addEventListener("DOMContentLoaded", () => {
  const logoutBtn = document.getElementById("logoutBtn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      clearAuth();
      window.location.href = "login.php";
    });
  }
});

// ✅ จัดการ error จาก axios
function handleAuthError(err) {
  if (err.response) {
    const status = err.response.status;

    if (status === 401) {
      console.warn("Unauthorized: clearing auth and redirecting to login");
      clearAuth();
      Swal.fire({
        icon: "warning",
        title: "Session expired",
        text: "กรุณาเข้าสู่ระบบใหม่",
        confirmButtonText: "OK"
      }).then(() => {
        window.location.href = "login.php";
      });
    } else if (status === 403) {
      Swal.fire({
        icon: "error",
        title: "Access denied",
        text: "คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้"
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Server error",
        text: err.response.data?.message || "เกิดข้อผิดพลาดจากเซิร์ฟเวอร์"
      });
    }
  } else if (err.request) {
    Swal.fire({
      icon: "error",
      title: "Network error",
      text: "ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้"
    });
  } else {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: err.message || "เกิดข้อผิดพลาดไม่ทราบสาเหตุ"
    });
  }
}

// ✅ export (ถ้าใช้ ES6 module)
// export { getUserData, getJWT, getRefreshToken, saveTokens, requireAuth, clearAuth, isTokenExpired, isRefreshExpired, handleAuthError };