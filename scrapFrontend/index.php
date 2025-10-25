<?php
$apiUrl = "https://scrapbackend.raffimrg.my.id/api/jadwal-kuliah";

$params = [];
if (!empty($_GET['nama_dosen'])) $params['nama_dosen'] = $_GET['nama_dosen'];
if (!empty($_GET['nama_mata_kuliah'])) $params['nama_mata_kuliah'] = $_GET['nama_mata_kuliah'];
if (!empty($_GET['id_ruang'])) $params['id_ruang'] = $_GET['id_ruang'];
if (!empty($_GET['semester'])) $params['semester'] = $_GET['semester'];
if (!empty($_GET['nama_hari'])) $params['nama_hari'] = $_GET['nama_hari'];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

$params['page'] = $page;
$params['limit'] = $limit;

if (!empty($params)) {
  $apiUrl .= '?' . http_build_query($params);
}

$response = @file_get_contents($apiUrl);
$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Jadwal Kuliah UNPAM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header class="header">
    <h1>📚 Jadwal Kuliah TI</h1>
    <button id="theme-toggle" class="theme-toggle">🌙 Mode Gelap</button>
  </header>

  <div class="card">
    <form method="get" class="filter-form">
      <input type="text" name="nama_dosen" placeholder="Cari Dosen" value="<?= htmlspecialchars($_GET['nama_dosen'] ?? '') ?>">
      <input type="text" name="nama_mata_kuliah" placeholder="Cari Mata Kuliah" value="<?= htmlspecialchars($_GET['nama_mata_kuliah'] ?? '') ?>">
      <input type="text" name="id_ruang" placeholder="Cari Ruangan" value="<?= htmlspecialchars($_GET['id_ruang'] ?? '') ?>">

      <select name="semester">
        <option value="">Semester</option>
        <?php for ($i = 1; $i <= 8; $i++):
          $val = sprintf("%02d", $i); ?>
          <option value="<?= $val ?>" <?= (($_GET['semester'] ?? '') === $val) ? 'selected' : '' ?>>
            Semester <?= $i ?>
          </option>
        <?php endfor; ?>
      </select>

      <select name="nama_hari">
        <option value="">Hari</option>
        <?php
        $days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        foreach ($days as $day): ?>
          <option value="<?= $day ?>" <?= (($_GET['nama_hari'] ?? '') === $day) ? 'selected' : '' ?>>
            <?= $day ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="limit">
        <?php foreach ([10, 25, 50, 100] as $opt): ?>
          <option value="<?= $opt ?>" <?= ($limit == $opt) ? 'selected' : '' ?>><?= $opt ?> / halaman</option>
        <?php endforeach; ?>
      </select>

      <button type="submit">🔍 Cari</button>
      <a href="index.php" class="reset-btn">Reset</a>
    </form>

    <?php if (!empty($data['data'])): ?>
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Mata Kuliah</th>
              <th>Dosen</th>
              <th>Hari</th>
              <th>Jam</th>
              <th>Ruang</th>
              <th>Kelas</th>
              <th>Semester</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['data'] as $i => $row): ?>
              <tr>
                <td><?= (($page - 1) * $limit) + $i + 1 ?></td>
                <td><?= htmlspecialchars($row['nama_mata_kuliah']) ?></td>
                <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                <td><?= htmlspecialchars($row['nama_hari']) ?></td>
                <td><?= htmlspecialchars($row['ket_jam']) ?></td>
                <td><?= htmlspecialchars($row['id_ruang']) ?></td>
                <td><?= htmlspecialchars($row['id_kelas']) ?></td>
                <td><?= htmlspecialchars($row['semester']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">&laquo; Sebelumnya</a>
        <?php endif; ?>

        <span>Halaman <?= $page ?> dari <?= $data['pages'] ?></span>

        <?php if ($page < $data['pages']): ?>
          <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Selanjutnya &raquo;</a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center; color:#888;">⚠️ Tidak ada data ditemukan.</p>
    <?php endif; ?>
  </div>
  <footer class="footer">
    <p>
      © <?= date("Y") ?> <strong>ME & GPT - Jadwal Kuliah TI </strong>Built because there was no filter / Search 🔎 in the original web.
    </p>
    <p class="footer-links">
      <a href="about.php">Tentang</a> |
      <a href="https://github.com/raffiMRG">Kontak</a>
      <!-- <a href="#">Kebijakan Privasi</a> -->
    </p>
  </footer>
  <script src="js/main.js"></script>
</body>

</html>
