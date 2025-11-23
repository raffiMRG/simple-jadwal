<?php
session_start();

// var_dump($_SESSION);

if (isset($_SESSION['jwt']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
  header("Location: index.php");
  exit;
}

$showSuccessRegister = false;
$showFalseLogin = false;

if (isset($_GET['registered']) && $_GET['registered'] === "true") {
  $showSuccessRegister = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    "username" => $_POST['username'],
    "password" => $_POST['password']
  ];

  // $ch = curl_init("https//scrapbackend.raffimrg.my.id/login");
  $ch = curl_init("http://localhost:82/login");

  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

  $result = curl_exec($ch);
  // curl_close($ch);
  unset($ch);

  $response = json_decode($result, true);

  if (isset($response['access_token'])) {
    $_SESSION['jwt'] = $response['access_token'];
    $_SESSION['username'] = $response['username'];
    $_SESSION['role'] = $response['role'];
    // echo "Login berhasil!";
    header("Location: index.php");
    exit;
  } else {
    $showFalseLogin = true;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal TI</title>
  <style>
    :root {
      --primary: #2563eb;
      --bg-salmonis: #d48787ff;
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
      /* background: var(--bg-light, #d48787ff); */
      background: url("img/3.webp") no-repeat fixed;
      background-size: cover;
      /* agar gambar memenuhi layar */
      background-position: center;
      /* posisi gambar selalu center */
      font-family: "Poppins", sans-serif;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .login-card {
      background: var(--primary);
      color: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      animation: fadeIn 0.4s ease;
    }

    .login-title {
      margin: 0;
      text-align: center;
      font-size: 26px;
      font-weight: 600;
    }

    .login-subtitle {
      text-align: center;
      margin: 5px 0 25px;
      opacity: 0.8;
    }

    .login-form {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .login-form label {
      font-size: 14px;
      opacity: 0.9;
    }

    .login-form input {
      padding: 12px;
      border-radius: 10px;
      border: none;
      outline: none;
      font-size: 15px;
    }

    .login-btn {
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

    .login-btn:hover {
      opacity: 0.85;
      transform: translateY(-1px);
    }

    .register-text {
      text-align: center;
      margin-top: 18px;
      font-size: 14px;
      opacity: 0.9;
    }

    .register-link {
      font-weight: 600;
      color: var(--primary);
      text-decoration: underline;
      cursor: pointer;
    }

    .register-link:hover {
      opacity: 0.85;
    }

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

    /* dark mode support */
    .dark body {
      background: #1c1c1c;
    }

    .dark .login-card {
      background: #2a2a2a;
    }

    .dark .login-btn {
      background: var(--primary);
      color: #2a2a2a;
    }

    .alert-success {
      position: fixed;
      top: 2vw;
      right: 2vw;
      background: #e2e7f1ff;
      color: #254c9fff;
      border-left: 5px solid #295ccaff;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      font-size: 14px;
      animation: fadeIn 0.4s ease-in-out;
    }

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
  <?php if ($showSuccessRegister): ?>
    <div class="alert-success" id="alert-success">
      ✔ Register berhasil! Silahkan login menggunakan akun yang didaftarkan.
    </div>
  <?php endif; ?>

  <?php if ($showFalseLogin): ?>
    <div class="alert-success" id="alert-success">
      ❌ Login gagal! Username atau password salah.
    </div>
  <?php endif; ?>

  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">🔐 Login</h2>

      <p class="login-subtitle">Silakan masuk untuk melanjutkan</p>

      <form action="" method="POST" class="login-form">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Masukkan username...">

        <label>Password</label>
        <input type="password" name="password" required placeholder="Masukkan password...">

        <button type="submit" class="login-btn">Login</button>
      </form>
    </div>
    <p class="register-text">
      Belum punya akun?
      <a href="register.php" class="register-link">Daftar sekarang</a>
    </p>

  </div>
</body>


</html>