<?php
require_once 'config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["password"] !== $_POST["password2"]) {
    $showPasswordModal = true; // trigger modal
  } else {
    $data = [
      "username" => $_POST["username"],
      "password" => $_POST["password"],
      "role"     => "user"
    ];

    $ch = curl_init("$API_URL/register");

    curl_setopt_array($ch, [
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
    ]);

    $result = curl_exec($ch);

    // ⚠ curl_close deprecated → gunakan unset()
    unset($ch);

    header("Location: login.php?registered=true");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <style>
    :root {
      --primary: #2563eb;
      --bg-light: #f9fafb;
      --bg-dark: #111827;
      --text-light: #1f2937;
      --text-dark: #f3f4f6;
      --table-border: #e5e7eb;
      --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      --radius: 12px;
    }

    body {
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      /* background: var(--background, #f2f2f2); */
      background: url("img/1.png") no-repeat fixed;
      background-size: cover;
      background-position: center;
      font-family: "Poppins", sans-serif;
    }

    .register-container {
      width: 100%;
      max-width: 440px;
      padding: 20px;
    }

    .register-card {
      background: var(--primary);
      color: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      animation: fadeIn 0.4s ease;
    }

    .register-title {
      text-align: center;
      margin: 0;
      font-size: 26px;
      font-weight: 600;
    }

    .register-subtitle {
      text-align: center;
      margin: 5px 0 25px;
      opacity: 0.8;
    }

    .register-form {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .register-form label {
      font-size: 14px;
      opacity: 0.9;
    }

    .register-form input {
      padding: 12px;
      border-radius: 10px;
      border: none;
      outline: none;
      font-size: 15px;
    }

    .register-btn {
      margin-top: 10px;
      padding: 12px;
      border: none;
      width: 100%;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
      background: #ffffff;
      color: var(--primary);
      font-weight: 600;
      transition: 0.25s;
    }

    .register-btn:hover {
      opacity: 0.85;
      transform: translateY(-1px);
    }

    .to-login-text {
      text-align: center;
      margin-top: 18px;
      font-size: 14px;
      opacity: 0.9;
    }

    .to-login-link {
      font-weight: 600;
      color: #ffffff;
      text-decoration: underline;
    }

    .to-login-link:hover {
      opacity: 0.85;
    }

    /* Alert */
    .alert {
      margin-top: 18px;
      padding: 12px;
      border-radius: 10px;
      font-size: 14px;
      text-align: center;
    }

    .alert-success {
      background: rgba(255, 255, 255, 0.2);
      color: #dbffdb;
    }

    .alert-error {
      background: rgba(255, 0, 0, 0.28);
      color: #ffeaea;
    }

    /* Animasi */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Dark mode support */
    .dark body {
      background: #1c1c1c;
    }

    .dark .register-card {
      background: #2a2a2a;
    }

    .dark .register-btn {
      background: var(--primary);
      color: white;
    }

    /* Modal background */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
    }

    /* Modal box */
    .modal-content {
      background: #ffffff;
      padding: 25px;
      width: 350px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 5px 18px rgba(0, 0, 0, 0.25);
      animation: fadeIn 0.3s ease-in-out;
    }

    .modal-content h3 {
      margin-top: 0;
      color: #d9534f;
    }

    .modal-content button {
      margin-top: 15px;
      padding: 10px 25px;
      background: #4a90e2;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .modal-content button:hover {
      background: #3c7bc2;
    }

    /* Animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <div class="register-container">
    <div class="register-card">
      <h2 class="register-title">📝 Daftar Akun</h2>
      <p class="register-subtitle">Silakan isi data untuk membuat akun baru</p>

      <form method="POST" class="register-form">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username..." required />

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password..." required />

        <label>Konfirmasi Password</label>
        <input type="password" name="password2" placeholder="Konfirmasi password yang sudah kamu masukan..." required />

        <button type="submit" class="register-btn">Register</button>
      </form>

      <p class="to-login-text">
        Sudah punya akun?
        <a href="login.php" class="to-login-link">Masuk di sini</a>
      </p>
    </div>
  </div>
  <!-- Modal -->
  <div id="errorModal" class="modal">
    <div class="modal-content">
      <h3>⚠ Password Tidak Sama</h3>
      <p>Pastikan password dan konfirmasi password sesuai.</p>
      <button id="closeModal">OK</button>
    </div>
  </div>
  <script>
    document.getElementById("closeModal").addEventListener("click", function() {
      document.getElementById("errorModal").style.display = "none";
    });

    // document.getElementById("registerForm").addEventListener("submit", function(e) {
    //   const password = document.getElementById("password").value;
    //   const confirmPassword = document.getElementById("confirmPassword").value;

    //   if (password !== confirmPassword) {
    //     e.preventDefault(); // cegah submit
    //     document.getElementById("errorModal").style.display = "flex";
    //   }
    // });

    // document.getElementById("closeModal").addEventListener("click", function() {
    //   document.getElementById("errorModal").style.display = "none";
    // });
  </script>
  <?php if (isset($showPasswordModal)): ?>
    <script>
      document.getElementById("errorModal").style.display = "flex";
    </script>
  <?php endif; ?>
</body>


</html>