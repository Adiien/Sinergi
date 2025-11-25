document.addEventListener("DOMContentLoaded", () => {
  // Ambil semua tombol comment
  const toggleButtons = document.querySelectorAll(".comment-toggle-button");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();

      // 1. Cari elemen pembungkus kartu (post-card) terdekat dari tombol yang diklik
      const card = button.closest(".post-card");

      if (card) {
        // 2. Cari div komentar (.comments-section) HANYA di dalam kartu tersebut
        const commentSection = card.querySelector(".comments-section");

        if (commentSection) {
          // 3. Toggle visibility
          commentSection.classList.toggle("hidden");
        } else {
          console.error("Section komentar tidak ditemukan di dalam kartu ini.");
        }
      } else {
        console.error("Elemen kartu (.post-card) tidak ditemukan.");
      }
    });
  });
});
