document.addEventListener("DOMContentLoaded", () => {
  // 1. Definisikan BASE_URL (harus ada di HTML)
  //    Kita akan tambahkan di home/index.php
  const BASE_URL = window.BASE_URL || "";

  // 2. Temukan semua tombol like
  const likeButtons = document.querySelectorAll(".like-button");

  likeButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      const currentButton = event.currentTarget;
      const postId = currentButton.dataset.postId;

      if (!postId) {
        console.error("Like button missing data-post-id");
        return;
      }

      const url = `${BASE_URL}/post/like?id=${postId}`;

      // 3. Kirim request ke server
      fetch(url)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          // 4. Proses balasan JSON dari server
          if (data.success) {
            // 4a. Update angka like count
            const countSpan = document.querySelector(
              `.like-count[data-post-id="${postId}"]`
            );
            if (countSpan) {
              countSpan.innerText = data.newLikeCount;
            }

            // 4b. Update tampilan tombol (styling)
            if (data.isLiked) {
              // User sekarang me-like
              currentButton.classList.add("text-blue-500", "font-bold");
              currentButton.classList.remove("text-gray-600");
            } else {
              // User sekarang tidak me-like
              currentButton.classList.remove("text-blue-500", "font-bold");
              currentButton.classList.add("text-gray-600");
            }
          } else {
            // Tampilkan error jika server gagal
            console.error("Like failed:", data.message);
            alert("Gagal melakukan like: " + data.message);
          }
        })
        .catch((error) => {
          // Tampilkan error jika fetch gagal
          console.error("Fetch error:", error);
          alert("Terjadi kesalahan. Coba lagi.");
        });
    });
  });
});
