  <!-- <header class="header">
    <h1>📚 Jadwal Kuliah TI</h1>
    <button id="theme-toggle" class="theme-toggle">🌙 Mode Gelap</button>
  </header> -->

  <nav class="navbar">
    <div class="navbar-left">
      <span class="navbar-icon">📚</span>
      <h1 class="navbar-title">Jadwal TI</h1>
    </div>

    <button class="hamburger" id="hamburger">
      ☰
    </button>
    <ul class="navbar-menu" id="navbar-menu">
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <li><a href="#">Home</a></li>
        <li><a href="#">Jadwal</a></li>
        <li><a href="#">Mata Kuliah</a></li>
      <?php endif; ?>
    </ul>

    <div class="navbar-right">
      <div class="profile" style="align-items: center; display: flex;">
        <span class="profile-name" style="padding: 10px;"><?= $_SESSION['username'] ?></span>
        <img src="https://i.pravatar.cc/40" alt="profile" class="profile-img" />
      </div>
      <button id="theme-toggle" class="theme-toggle">🌙</button>
      <button id="logout-btn" class="logout-btn">🚪 Logout</button>
    </div>
  </nav>