const body = document.body;
const toggleBtn = document.getElementById("theme-toggle");
const hamburger = document.getElementById("hamburger");
const navbarMenu = document.getElementById("navbar-menu");
const navbarRight = document.querySelector(".navbar-right");

// Cek apakah user pernah simpan preferensi mode
if (localStorage.getItem("theme") === "dark") {
  body.classList.add("dark");
  toggleBtn.textContent = "☀️";
}

toggleBtn.addEventListener("click", () => {
  body.classList.toggle("dark");
  const isDark = body.classList.contains("dark");
  toggleBtn.textContent = isDark ? "☀️" : "🌙";
  localStorage.setItem("theme", isDark ? "dark" : "light");
});

hamburger.addEventListener("click", () => {
  navbarMenu.classList.toggle("show");
});

// simpan referensi elemen untuk dipindahkan
const profile = navbarRight.querySelector(".profile");
const themeToggle = navbarRight.querySelector("#theme-toggle");
const btnLogout = navbarRight.querySelector(".logout-btn");

// buat <li> untuk mobile
let liProfile = null;
let liTheme = null;
let liLogout = null;

function moveNavbarRight() {
  if (window.innerWidth < 800) {
    // jika belum dipindahkan
    if (!liProfile) {
      liProfile = document.createElement("li");
      liProfile.appendChild(profile);
      navbarMenu.appendChild(liProfile);
    }
    if (!liTheme) {
      liTheme = document.createElement("li");
      liTheme.appendChild(themeToggle);
      navbarMenu.appendChild(liTheme);
    }
    if (!liLogout) {
      liLogout = document.createElement("li");
      liLogout.appendChild(btnLogout);
      navbarMenu.appendChild(liLogout);
    }
  } else {
    // kembalikan jika ukuran layar besar
    if (liProfile) {
      navbarRight.appendChild(profile);
      liProfile.remove();
      liProfile = null;
    }
    if (liTheme) {
      navbarRight.appendChild(themeToggle);
      liTheme.remove();
      liTheme = null;
    }
    if (liLogout) {
      navbarRight.appendChild(btnLogout);
      liLogout.remove();
      liLogout = null;
    }
  }
}

// panggil saat halaman load & resize
window.addEventListener("load", moveNavbarRight);
window.addEventListener("resize", moveNavbarRight);

document.getElementById("logout-btn").addEventListener("click", () => {
  window.location.href = "logout.php";
});
