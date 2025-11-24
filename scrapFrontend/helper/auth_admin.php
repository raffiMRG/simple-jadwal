<?php
if (!isset($_SESSION['jwt']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
  // exit("Harus login terlebih dahulu.");
  header("Location: login.php");
  die();
}
if ($_SESSION['role'] !== 'admin') {
  // exit("Harus login terlebih dahulu.");
  header("Location: 403.php");
  die();
}
