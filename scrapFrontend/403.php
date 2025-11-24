<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 Forbidden - Access Denied</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #f4f4faff;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
      font-family: Arial, sans-serif;
    }

    .box {
      text-align: center;
      background: white;
      padding: 40px 60px;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, .15);
      margin-top: 30px;
    }

    h1 {
      font-size: 60px;
      margin: 0;
      color: #c0392b;
    }

    h2 {
      margin-bottom: 10px;
      color: #333;
    }

    p {
      margin-top: 8px;
      color: #666;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 16px;
      background: #2980b9;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    a:hover {
      background: #1f6691;
    }
  </style>
</head>

<body>

  <img src="img/4.png" alt="403 Forbidden" width="400">
  <div class="box">
    <h1>403</h1>
    <h2>Access Denied</h2>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="index.php">Kembali ke Dashboard</a>
  </div>

</body>

</html>