document.addEventListener("DOMContentLoaded", () => {
  const dropdownButton = document.getElementById("profile-dropdown-button");
  const dropdownMenu = document.getElementById("profile-dropdown-menu");

  if (dropdownButton && dropdownMenu) {
    // Toggle dropdown saat tombol diklik
    dropdownButton.addEventListener("click", () => {
      dropdownMenu.classList.toggle("hidden");
    });

    // Tutup dropdown saat mengklik di luar area dropdown
    window.addEventListener("click", (event) => {
      const container = document.getElementById("profile-dropdown-container");
      // Pastikan klik terjadi di luar container
      if (container && !container.contains(event.target)) {
        dropdownMenu.classList.add("hidden");
      }
    });

    // Tutup dropdown saat menekan tombol 'Escape'
    window.addEventListener("keydown", (event) => {
      if (event.key === "Escape" || event.key === "Esc") {
        dropdownMenu.classList.add("hidden");
      }
    });
  }
});
