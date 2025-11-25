document.addEventListener("DOMContentLoaded", () => {
  const BASE_URL = window.BASE_URL || "";

  // Ambil semua tombol dengan class .follow-button
  const followButtons = document.querySelectorAll(".follow-button");

  followButtons.forEach((button) => {
    button.addEventListener("click", async (event) => {
      event.preventDefault(); // Mencegah refresh halaman

      const btn = event.currentTarget;
      const userId = btn.dataset.userId;

      // Simpan text asli untuk berjaga-jaga jika error
      const originalText = btn.innerText;
      const isFollowing = originalText.trim() === "Following";

      // --- 1. OPTIMISTIC UI (Ubah tampilan duluan biar cepat) ---
      if (isFollowing) {
        // User mau UNFOLLOW
        btn.innerText = "Follow";

        // Ganti style ke "Follow" (Biru Outline)
        btn.classList.remove("bg-gray-100", "text-gray-500", "border-gray-200");
        btn.classList.add(
          "border-blue-500",
          "text-blue-500",
          "hover:bg-blue-50"
        );
      } else {
        // User mau FOLLOW
        btn.innerText = "Following";

        // Ganti style ke "Following" (Abu-abu Solid)
        btn.classList.remove(
          "border-blue-500",
          "text-blue-500",
          "hover:bg-blue-50"
        );
        btn.classList.add("bg-gray-100", "text-gray-500", "border-gray-200");
      }

      // --- 2. KIRIM KE SERVER ---
      try {
        const response = await fetch(`${BASE_URL}/user/follow?id=${userId}`);
        const data = await response.json();

        if (!data.success) {
          throw new Error(data.message || "Gagal memproses follow");
        }

        // Jika sukses, biarkan tampilan yang sudah berubah.
        // (Opsional) Anda bisa update angka followers di sidebar kiri secara real-time di sini
        // jika Anda ingin fitur yang lebih canggih.
      } catch (error) {
        console.error("Follow error:", error);
        alert("Terjadi kesalahan jaringan. Perubahan dibatalkan.");

        // Rollback: Kembalikan tampilan tombol ke semula jika gagal
        btn.innerText = originalText;
        if (isFollowing) {
          // Balik ke Following
          btn.classList.remove(
            "border-blue-500",
            "text-blue-500",
            "hover:bg-blue-50"
          );
          btn.classList.add("bg-gray-100", "text-gray-500", "border-gray-200");
        } else {
          // Balik ke Follow
          btn.classList.remove(
            "bg-gray-100",
            "text-gray-500",
            "border-gray-200"
          );
          btn.classList.add(
            "border-blue-500",
            "text-blue-500",
            "hover:bg-blue-50"
          );
        }
      }
    });
  });
});
