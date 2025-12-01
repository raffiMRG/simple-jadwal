  <!-- <header class="header">
    <h1>📚 Jadwal Kuliah TI</h1>
    <button id="theme-toggle" class="theme-toggle">🌙 Mode Gelap</button>
  </header> -->

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

    /* Navbar container */
    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--primary);
      padding: 1rem 1.2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 100;
      width: 100%;
    }

    /* Left side */
    .navbar-left {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .navbar-icon {
      font-size: 24px;
    }

    /* Title */
    .navbar-title {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
    }

    /* Menu */
    .navbar-menu {
      list-style: none;
      display: flex;
      align-items: center;
      gap: 1.2rem;
      margin: 0;
      padding: 0;
    }

    .navbar-menu li a {
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: 0.2s;
    }

    .navbar-menu li a:hover {
      opacity: 0.8;
    }

    /* Right */
    .navbar-right {
      display: flex;
      align-items: center;
      gap: 0.7rem;
    }

    .profile-img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
    }

    .profile-name {
      font-size: 15px;
      font-weight: 500;
      color: var(--text-dark);
    }

    /* Hamburger (mobile only) */
    .hamburger {
      display: none;
      background: none;
      border: none;
      font-size: 26px;
      cursor: pointer;
      color: white;
    }

    .logout-btn {
      padding: 8px 15px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      background: #d9534f;
      color: white;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: 0.2s;
    }

    .logout-btn:hover {
      opacity: 0.85;
    }

    .theme-toggle {
      background: white;
      color: var(--primary);
      border: none;
      padding: 0.5rem 1rem;
      border-radius: var(--radius);
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .theme-toggle:hover {
      background: #e0e7ff;
    }

    /* h1 {
      color: var(--primary);
      margin: 20px 0;
      text-align: center;
    } */


    /* Header H1 agar kontras di kedua mode */
    h1 {
      color: var(--text-dark);
      font-weight: 700;
      font-size: 1.8rem;
      text-align: center;
    }


    /* Responsive handling */
    @media (max-width: 800px) {
      .navbar-menu {
        position: absolute;
        top: 72px;
        right: 0;
        background: var(--primary);
        width: 200px;
        flex-direction: column;
        padding: 1rem;
        gap: 1rem;
        border-radius: 0 0 10px 10px;
        box-shadow: var(--shadow);
        display: none;
      }

      .navbar-menu.show {
        display: flex;
      }

      .hamburger {
        display: block;
      }

      /* hide menu on mobile when not expanded */
      .navbar-right {
        display: none;
      }

      h1 {
        font-size: 1.5rem;
      }
    }

    @media (min-width: 801px) {

      /* hamburger only mobile */
      .hamburger {
        display: none;
      }
    }
  </style>

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
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="users.php">Account</a></li>
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