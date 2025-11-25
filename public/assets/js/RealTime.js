document.addEventListener("DOMContentLoaded", () => {
  const BASE_URL = window.BASE_URL || "";

  // Fungsi untuk melakukan polling
  function fetchUpdates() {
    // 1. Kumpulkan semua ID postingan yang sedang tampil di layar
    // Kita gunakan elemen .like-count sebagai patokan untuk mencari ID
    const likeCounts = document.querySelectorAll(".like-count");
    let postIds = [];

    likeCounts.forEach((span) => {
      if (span.dataset.postId) {
        postIds.push(span.dataset.postId);
      }
    });

    // Jika tidak ada postingan, hentikan
    if (postIds.length === 0) return;

    // 2. Kirim request ke server
    fetch(`${BASE_URL}/api/updates`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ post_ids: postIds }),
    })
      .then((response) => response.json())
      .then((data) => {
        // 3. Update tampilan HTML dengan data baru
        data.forEach((item) => {
          const pid = item.POST_ID; // Pastikan case sensitif sesuai database (biasanya UPPERCASE di Oracle)

          // Update Angka Like
          const likeSpan = document.querySelector(
            `.like-count[data-post-id="${pid}"]`
          );
          if (likeSpan) {
            likeSpan.innerText = item.LIKE_COUNT;
          }

          // Update Angka Comment
          const commentSpan = document.querySelector(
            `.comment-count[data-post-id="${pid}"]`
          );
          if (commentSpan) {
            commentSpan.innerText = item.COMMENT_COUNT;
          }
        });
      })
      .catch((err) => {
        // Error diam (silent fail) agar tidak mengganggu user
        // console.error(err);
      });
  }

  // Jalankan fungsi fetchUpdates setiap 3000ms (3 detik)
  setInterval(fetchUpdates, 3000);
});
