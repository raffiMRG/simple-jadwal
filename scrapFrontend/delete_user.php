<?php
session_start();
require_once 'helper/auth_admin.php';
$token = $_SESSION['jwt'];
$API_URL = "http://localhost:82";
$id = $_GET["id"];
$apiUrl = "$API_URL/api/users/$id";

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Accept: application/json"
]);
curl_exec($ch);
curl_close($ch);

header("Location: users.php");
exit;
