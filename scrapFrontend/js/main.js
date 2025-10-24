const toggleBtn = document.getElementById("theme-toggle");
const body = document.body;

// Cek apakah user pernah simpan preferensi mode
if (localStorage.getItem("theme") === "dark") {
  body.classList.add("dark");
  toggleBtn.textContent = "☀️ Mode Terang";
}

toggleBtn.addEventListener("click", () => {
  body.classList.toggle("dark");
  const isDark = body.classList.contains("dark");
  toggleBtn.textContent = isDark ? "☀️ Mode Terang" : "🌙 Mode Gelap";
  localStorage.setItem("theme", isDark ? "dark" : "light");
});
