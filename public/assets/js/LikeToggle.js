document.addEventListener("DOMContentLoaded", () => {
  const BASE_URL = window.BASE_URL || "";

  // Gunakan Event Delegation pada document body agar elemen dinamis juga tercover
  document.body.addEventListener("click", (event) => {
    // Cek apakah yang diklik adalah tombol like (atau icon di dalamnya)
    const likeButton = event.target.closest(".like-button");

    if (likeButton) {
      event.preventDefault();
      const postId = likeButton.dataset.postId;

      // --- [BAGIAN 1: OPTIMISTIC UI - UPDATE SEMUA DUPLIKAT] ---
      // Kita cari SEMUA elemen (tombol & angka) yang punya ID Postingan ini
      const allLikeButtons = document.querySelectorAll(
        `.like-button[data-post-id="${postId}"]`
      );
      const allLikeCounts = document.querySelectorAll(
        `.like-count[data-post-id="${postId}"]`
      );

      // Cek status saat ini dari tombol yang diklik
      const isCurrentlyLiked = likeButton.classList.contains("text-blue-500");

      // Update Tampilan SEMUA Tombol (Content & Activity)
      allLikeButtons.forEach((btn) => {
        if (isCurrentlyLiked) {
          // Jadi Unlike
          btn.classList.remove("text-blue-500", "font-bold");
          btn.classList.add("text-gray-600");
        } else {
          // Jadi Like
          btn.classList.add("text-blue-500", "font-bold");
          btn.classList.remove("text-gray-600");
        }
      });

      // Update Tampilan SEMUA Angka
      allLikeCounts.forEach((span) => {
        let currentCount = parseInt(span.innerText) || 0;
        if (isCurrentlyLiked) {
          span.innerText = Math.max(0, currentCount - 1);
        } else {
          span.innerText = currentCount + 1;
        }
      });

      // --- [BAGIAN 2: KIRIM KE SERVER] ---
      const url = `${BASE_URL}/post/like?id=${postId}&_=${new Date().getTime()}`;

      fetch(url)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Sinkronisasi ulang angka dari server ke SEMUA tempat
            allLikeCounts.forEach((span) => {
              span.innerText = data.newLikeCount;
            });
          } else {
            // Jika GAGAL, Rollback (kembalikan tampilan)
            alert("Gagal like: " + data.message);
            location.reload();
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    }
  });
});
