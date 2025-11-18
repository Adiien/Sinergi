document.addEventListener("DOMContentLoaded", () => {
  // Ambil elemen-elemen yang diperlukan
  const mainNav = document.getElementById("main-nav");
  const mainContent = document.getElementById("main-content");
  const postModal = document.getElementById("create-post-modal");

  // Ambil semua pemicu (area abu-abu DAN tombol post palsu)
  const openTrigger = document.getElementById("create-post-trigger");
  const openTriggerButton = document.getElementById(
    "create-post-trigger-button"
  );

  const closeButton = document.getElementById("close-post-modal");

  // Pastikan semua elemen ada
  if (
    !mainNav ||
    !mainContent || // Pastikan perbaikan HTML (Langkah 1) sudah dilakukan
    !postModal ||
    !openTrigger ||
    !closeButton
  ) {
    console.error(
      "Satu atau lebih elemen modal postingan (nav, content, modal, trigger, close) tidak ditemukan!"
    );
    return;
  }

  // Latar belakang yang akan di-blur
  const backgroundElements = [mainNav, mainContent];

  // Fungsi untuk mem-blur latar belakang
  function blurBackground() {
    backgroundElements.forEach((el) => {
      el.classList.add("blur-sm", "pointer-events-none");
    });
  }

  // Fungsi untuk mengembalikan latar belakang
  function unblurBackground() {
    backgroundElements.forEach((el) => {
      el.classList.remove("blur-sm", "pointer-events-none");
    });
  }

  // Fungsi untuk menampilkan modal
  function showModal() {
    blurBackground();
    postModal.classList.remove("hidden");
    setTimeout(() => {
      postModal.classList.remove("opacity-0", "scale-95");
    }, 20); // Sedikit delay agar transisi CSS terbaca
  }

  // Fungsi untuk menyembunyikan modal
  function hideModal() {
    unblurBackground();
    postModal.classList.add("opacity-0", "scale-95");
    setTimeout(() => {
      postModal.classList.add("hidden");
    }, 300); // Waktu ini harus cocok dengan durasi transisi CSS Anda
  }

  // --- Tambahkan Event Listeners ---

  // Klik pemicu akan membuka modal
  openTrigger.addEventListener("click", showModal);
  if (openTriggerButton) {
    openTriggerButton.addEventListener("click", showModal);
  }

  // Klik tombol 'X' akan menutup modal
  closeButton.addEventListener("click", hideModal);

  // Klik di luar card (di area overlay gelap) akan menutup modal
  postModal.addEventListener("click", (e) => {
    // Cek apakah yang diklik adalah 'postModal' (overlay)
    // dan BUKAN 'e.target.closest' (bukan card atau isinya)
    if (e.target === postModal) {
      hideModal();
    }
  });

  // Tekan 'Escape' akan menutup modal
  window.addEventListener("keydown", (event) => {
    if (event.key === "Escape" || event.key === "Esc") {
      // Cek apakah modal sedang tidak hidden
      if (!postModal.classList.contains("hidden")) {
        hideModal();
      }
    }
  });
});
