document.addEventListener("DOMContentLoaded", () => {
  // Fungsi untuk menutup SEMUA dropdown menu yang terbuka
  function closeAllPostMenus() {
    document.querySelectorAll(".post-menu-dropdown").forEach((menu) => {
      menu.classList.add("hidden");
    });
  }

  // Cari semua tombol ...
  const menuButtons = document.querySelectorAll(".post-menu-button");

  menuButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation(); // Mencegah window.click terpicu

      const menuId = button.dataset.targetMenuId;
      const targetMenu = document.getElementById(`post-menu-${menuId}`);

      if (targetMenu) {
        // Cek apakah menu ini sedang terbuka
        const isHidden = targetMenu.classList.contains("hidden");

        // Tutup semua menu lain dulu
        closeAllPostMenus();

        // Jika menu ini tadinya tertutup, buka
        if (isHidden) {
          targetMenu.classList.remove("hidden");
        }
        // Jika tadinya terbuka, biarkan tertutup (karena sudah ditutup oleh closeAllPostMenus)
      }
    });
  });

  // Listener global untuk menutup menu jika klik di luar
  window.addEventListener("click", (event) => {
    // Cek apakah yang diklik bukan di dalam area menu
    if (!event.target.closest("[data-menu-container]")) {
      closeAllPostMenus();
    }
  });

  // Tutup juga saat menekan tombol 'Escape'
  window.addEventListener("keydown", (event) => {
    if (event.key === "Escape" || event.key === "Esc") {
      closeAllPostMenus();
    }
  });
});
