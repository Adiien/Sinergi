document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("createPostForm");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // Mencegah reload halaman (local host redirect)

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerText;

      // 1. Ubah tombol jadi loading
      submitBtn.innerText = "Posting...";
      submitBtn.disabled = true;
      submitBtn.classList.add("opacity-50", "cursor-not-allowed");

      // 2. Ambil data form (Otomatis mengambil nilai checkbox jika dicentang)
      const formData = new FormData(this);

      // 3. Kirim via AJAX (Fetch)
      fetch(this.action, {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // BERHASIL

            // Reset Form (Kosongkan input & uncheck checkbox)
            form.reset();

            // Opsi A: Reload halaman otomatis agar postingan baru muncul
            window.location.reload();

            // Opsi B: Jika ingin tanpa reload sama sekali, Anda harus
            // menambahkan logika insert HTML post baru ke feed di sini.
          } else {
            // GAGAL (Tampilkan alert)
            alert("Gagal memposting: " + data.message);
          }
        })
        .catch((err) => {
          console.error(err);
          alert("Terjadi kesalahan sistem. Coba lagi.");
        })
        .finally(() => {
          // Kembalikan tombol ke semula
          submitBtn.innerText = originalText;
          submitBtn.disabled = false;
          submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
        });
    });
  }
});
