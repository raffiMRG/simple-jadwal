<?php
session_start();
require_once 'helper/auth_admin.php';
require_once 'config/config.php';

$token = $_SESSION['jwt'];

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$apiUrl = "$API_URL/api/users?page=$page&limit=$limit";

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Accept: application/json"
]);
$response = curl_exec($ch);
unset($ch);

$data = json_decode($response, true);
$users = $data["data"];
$total_pages = $data["total_pages"];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Akun</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

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
      background: var(--bg-light, #f3f4f6);
      padding: 20px;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      /* background: white; */
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
    }

    h2 {
      margin-bottom: 18px;
      font-size: 26px;
      font-weight: 600;
      color: var(--text-color);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th {
      background: #2563eb;
      color: white;
      padding: 10px;
      text-align: center;
    }

    td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #e5e7eb;
    }

    tr:hover td {
      background: #f1f5f9;
    }

    .button {
      padding: 7px 12px;
      border-radius: 6px;
      text-decoration: none;
      color: white;
      font-size: 14px;
      transition: 0.2s;
      display: inline-block;
      margin: 2px;
    }

    .btn-primary {
      background: #2563eb;
    }

    .btn-primary:hover {
      background: #1d4ed8;
    }

    .btn-danger {
      background: #dc2626;
    }

    .btn-danger:hover {
      background: #b91c1c;
    }

    /* Pagination */
    .pagination {
      margin-top: 18px;
      text-align: center;
    }

    .pagination a {
      margin: 2px;
      padding: 6px 12px;
      background: #6b7280;
      color: white;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
    }

    .pagination a:hover,
    .pagination a.active {
      background: #374151;
    }

    @media (max-width: 700px) {

      td:nth-child(1),
      th:nth-child(1) {
        display: none;
      }
    }

    /* ---------------- DARK MODE ---------------- */
    body.dark {
      background-color: var(--bg-dark);
      color: var(--text-dark);
    }

    body.dark th {
      background-color: #1f6feb;
    }

    body.dark th {
      background-color: #1f6feb;
    }

    body.dark tr:nth-child(even) {
      background-color: #1c1f26;
    }

    body.dark tr:nth-child(odd) {
      background-color: #20252b;
    }

    body.dark th,
    body.dark td {
      border-bottom: 1px solid #374151;
    }


    body.dark .filter-form input,
    body.dark .filter-form select,
    body.dark .filter-form button {
      background: #1f2937;
      color: var(--text-dark);
      border: 1px solid #374151;
    }

    body.dark .theme-toggle {
      background: #1f2937;
      color: var(--text-dark);
    }

    body.dark .footer {
      background: #1f2937;
      color: #9ca3af;
    }
  </style>
</head>

<body>
  <?php require_once 'navbar.php' ?>

  <div class="container">
    <h2>Daftar Akun</h2>

    <table>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
      </tr>

      <?php foreach ($users as $u) { ?>
        <tr>
          <td><?= $u["id"] ?></td>
          <td><?= $u["username"] ?></td>
          <td>
            <?= $u["role"] == "admin"
              ? "<strong style='color:#2563eb;'>Admin</strong>"
              : "<span style='color:#111;'>User</span>" ?>
          </td>
          <td>
            <a class="button btn-primary" href="edit_role.php?id=<?= $u['id'] ?>">Edit Role</a>
            <a class="button btn-danger"
              href="delete_user.php?id=<?= $u['id'] ?>"
              onclick="return confirm('Yakin ingin menghapus akun ini?');">
              Hapus
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>

    <div class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
      <?php } ?>
    </div>
  </div>

  <script src="js/main.js"></script>
</body>

</html>