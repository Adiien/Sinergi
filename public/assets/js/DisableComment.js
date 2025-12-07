document.addEventListener("DOMContentLoaded", () => {
  // Cari semua tombol dengan class 'disable-comment-btn'
  const disableButtons = document.querySelectorAll(".disable-comment-btn");

  disableButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // Mencegah reload/navigasi link

      const url = this.getAttribute("data-url");
      const postId = this.getAttribute("data-post-id");
      const iconContainer = this.querySelector(".icon-container");
      const textLabel = this.querySelector(".text-label");

      // UI Container Komentar (Form input komentar)
      const commentSection = document.getElementById(
        `comments-section-${postId}`
      );
      // Kita cari form di dalamnya untuk di-hide/show
      const commentForm = commentSection
        ? commentSection.querySelector("form")
        : null;
      // Atau pesan 'Komentar dinonaktifkan' jika ada

      // Lakukan Request AJAX
      fetch(url)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // 1. Update Tampilan Tombol & Icon
            if (data.isDisabled) {
              // Jika SEKARANG disabled, tombol berubah jadi "Aktifkan"
              textLabel.textContent = "Aktifkan Komentar";
              iconContainer.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>`;

              // Sembunyikan Form Komentar di UI
              if (commentForm) {
                commentForm.style.display = "none";
                // Opsional: Tambahkan pesan teks pengganti
                let msgDiv = commentSection.querySelector(".disabled-msg");
                if (!msgDiv) {
                  msgDiv = document.createElement("div");
                  msgDiv.className =
                    "disabled-msg bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4 text-center text-sm text-gray-500 italic";
                  msgDiv.innerHTML = "Komentar telah dinonaktifkan.";
                  commentSection.insertBefore(
                    msgDiv,
                    commentSection.firstChild
                  );
                } else {
                  msgDiv.style.display = "block";
                }
              }
            } else {
              // Jika SEKARANG enabled, tombol berubah jadi "Nonaktifkan"
              textLabel.textContent = "Nonaktifkan Komentar";
              iconContainer.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>`;

              // Tampilkan Kembali Form Komentar
              if (commentForm) commentForm.style.display = "flex"; // atau block
              const msgDiv = commentSection
                ? commentSection.querySelector(".disabled-msg")
                : null;
              if (msgDiv) msgDiv.style.display = "none";
            }

            // Tutup dropdown menu agar rapi
            const menuDropdown = button.closest(".post-menu-dropdown");
            if (menuDropdown) menuDropdown.classList.add("hidden");
          } else {
            alert("Gagal mengubah status: " + data.message);
          }
        })
        .catch((err) => {
          console.error(err);
          alert("Terjadi kesalahan koneksi.");
        });
    });
  });
});
