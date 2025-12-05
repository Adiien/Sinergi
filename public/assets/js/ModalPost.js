document.addEventListener("DOMContentLoaded", () => {
  // 1. Ambil elemen-elemen Kritis (Wajib Ada)
  const postModal = document.getElementById("create-post-modal");
  const closeButton = document.getElementById("close-post-modal");

  // Jika modal atau tombol close tidak ada, hentikan script (ini error HTML)
  if (!postModal || !closeButton) {
    console.error("Elemen modal atau tombol close tidak ditemukan di HTML.");
    return;
  }

  // 2. Ambil elemen Trigger (Input Postingan)
  // Ini BISA NULL jika user belum join forum (karena disembunyikan PHP)
  const openTrigger = document.getElementById("create-post-trigger");
  const openTriggerButton = document.getElementById(
    "create-post-trigger-button"
  );

  // Jika tidak ada pemicu (artinya user bukan member/belum login),
  // script berhenti di sini tanpa Error.
  if (!openTrigger && !openTriggerButton) {
    return;
  }

  // 3. Ambil elemen Background untuk efek Blur (Opsional)
  // Kita gunakan pengecekan agar tidak error jika id="main-nav" tidak ada
  const mainNav = document.getElementById("main-nav");
  const mainContent = document.getElementById("main-content");

  const backgroundElements = [];
  if (mainNav) backgroundElements.push(mainNav);
  if (mainContent) backgroundElements.push(mainContent);

  // --- Fungsi-fungsi ---

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
    // Sedikit delay agar transisi opacity terbaca oleh browser
    setTimeout(() => {
      postModal.classList.remove("opacity-0", "scale-95");
    }, 20);
  }

  // Fungsi untuk menyembunyikan modal
  function hideModal() {
    unblurBackground();
    postModal.classList.add("opacity-0", "scale-95");
    // Tunggu durasi transisi (300ms) baru sembunyikan elemen
    setTimeout(() => {
      postModal.classList.add("hidden");
    }, 300);
  }

  // --- Event Listeners ---

  // Hanya tambahkan listener jika elemen trigger ditemukan
  if (openTrigger) {
    openTrigger.addEventListener("click", showModal);
  }

  if (openTriggerButton) {
    openTriggerButton.addEventListener("click", showModal);
  }

  // Tombol Close
  closeButton.addEventListener("click", hideModal);

  // Klik di luar card (overlay) menutup modal
  postModal.addEventListener("click", (e) => {
    if (e.target === postModal) {
      hideModal();
    }
  });

  // Tombol Escape menutup modal
  window.addEventListener("keydown", (event) => {
    if (event.key === "Escape" || event.key === "Esc") {
      if (!postModal.classList.contains("hidden")) {
        hideModal();
      }
    }
  });
});
