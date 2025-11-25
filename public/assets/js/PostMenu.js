document.addEventListener("DOMContentLoaded", () => {
  // Fungsi untuk menutup SEMUA dropdown menu yang terbuka
  function closeAllPostMenus() {
    document.querySelectorAll(".post-menu-dropdown").forEach((menu) => {
      menu.classList.add("hidden");
    });
  }

  // Cari semua tombol titik tiga
  const menuButtons = document.querySelectorAll(".post-menu-button");

  menuButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation(); // Mencegah event bubbling

      // 1. Cari container pembungkus (parent) dari tombol ini
      const container = button.closest("[data-menu-container]");

      if (container) {
        // 2. Cari menu dropdown HANYA di dalam container ini
        const targetMenu = container.querySelector(".post-menu-dropdown");

        if (targetMenu) {
          // Cek apakah menu ini sedang terbuka
          const isHidden = targetMenu.classList.contains("hidden");

          // Tutup semua menu lain dulu agar rapi
          closeAllPostMenus();

          // Jika menu ini tadinya tertutup, buka sekarang
          if (isHidden) {
            targetMenu.classList.remove("hidden");
          }
        }
      }
    });
  });

  // Listener global untuk menutup menu jika klik di luar
  window.addEventListener("click", (event) => {
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
