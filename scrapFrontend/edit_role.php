<?php
session_start();
require_once 'helper/auth_admin.php';
require_once 'config/config.php';

$token = $_SESSION['jwt'];
$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $role = $_POST["role"];
  $payload = json_encode(["role" => $role]);
  $apiUrl = "$API_URL/api/users/$id/role";

  $ch = curl_init($apiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Accept: application/json",
    "Content-Type: application/json",
    "Content-Length: " . strlen($payload)
  ]);

  curl_exec($ch);
  curl_close($ch);

  header("Location: users.php");
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Role Akun</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: #f3f4f6;
      padding: 20px;
    }

    .container {
      max-width: 450px;
      background: white;
      margin: 60px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      font-size: 24px;
      font-weight: 600;
      color: #111;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-size: 15px;
      text-align: left;
      color: #333;
    }

    select {
      width: 100%;
      padding: 10px;
      font-size: 15px;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      outline: none;
      margin-bottom: 18px;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #2563eb;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: .2s;
    }

    button:hover {
      background: #1d4ed8;
    }

    .btn-back {
      display: block;
      text-align: center;
      margin-top: 15px;
      padding: 9px;
      font-size: 15px;
      border-radius: 6px;
      color: white;
      background: #374151;
      text-decoration: none;
      transition: .2s;
    }

    .btn-back:hover {
      background: #1f2937;
    }
  </style>
</head>

<body>


  <div class="container">
    <h2>Ubah Role Akun<br><small>(ID <?= $id ?>)</small></h2>

    <form method="POST">
      <label>Pilih Role:</label>
      <select name="role" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit">Simpan</button>
      <a href="users.php" class="btn-back">Kembali</a>
    </form>
  </div>

</body>

</html>