document.addEventListener("DOMContentLoaded", () => {
  // 1. Temukan SEMUA tombol 'comment' di halaman
  const toggleButtons = document.querySelectorAll(".comment-toggle-button");

  // 2. Tambahkan event listener ke setiap tombol
  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // 3. Ambil post ID dari data-attribut tombol yang diklik
      const postId = button.dataset.postId;
      if (!postId) {
        console.error("Tombol tidak memiliki data-post-id");
        return;
      }

      // 4. Bangun ID unik untuk section komentar
      const commentSectionId = "comments-section-" + postId;
      const commentSection = document.getElementById(commentSectionId);

      if (commentSection) {
        // 5. Tampilkan/sembunyikan (toggle) section komentar
        commentSection.classList.toggle("hidden");
      } else {
        console.error(
          "Section komentar " + commentSectionId + " tidak ditemukan"
        );
      }
    });
  });
});
