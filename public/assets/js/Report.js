document.addEventListener("DOMContentLoaded", () => {
  const BASE_URL = window.BASE_URL || "";

  // Ambil elemen-elemen modal (tetap sama)
  const reportModal = document.getElementById("report-modal");
  const closeButton = document.getElementById("close-report-modal");
  const cancelButton = document.getElementById("cancel-report-button");
  const reportForm = document.getElementById("report-form");
  const hiddenTargetId = document.getElementById("report-target-id");
  const hiddenTargetType = document.getElementById("report-target-type");

  // Variabel untuk menyimpan tombol yang diklik (tetap sama)
  let lastClickedReportButton = null;

  if (!reportModal || !closeButton || !cancelButton || !reportForm) {
    console.error("Elemen modal report tidak ditemukan!");
    return;
  }

  // Fungsi untuk menampilkan modal (tetap sama)
  function showModal() {
    reportModal.classList.remove("hidden");
    reportForm.reset();
  }

  // Fungsi untuk menyembunyikan modal (tetap sama)
  function hideModal() {
    reportModal.classList.add("hidden");
  }

  // --- [PERUBAHAN UTAMA DI SINI] ---
  // Gunakan Event Delegation
  // Kita pasang 1 listener di 'document.body'
  // Listener ini akan menangkap klik dari manapun
  document.body.addEventListener("click", function (event) {
    // Cek apakah elemen yang diklik (atau parent-nya) adalah '.report-button'
    const reportButton = event.target.closest(".report-button");

    // Jika BUKAN tombol report, abaikan
    if (!reportButton) {
      return;
    }

    // Jika YA, jalankan logika kita
    const targetType = reportButton.dataset.targetType;
    const targetId = reportButton.dataset.targetId;

    if (!targetType || !targetId) {
      console.error(
        "Tombol report tidak memiliki data-target-type atau data-target-id"
      );
      return;
    }

    // Simpan data ke hidden input di dalam modal
    hiddenTargetId.value = targetId;
    hiddenTargetType.value = targetType;

    // Simpan tombol yang diklik
    lastClickedReportButton = reportButton;

    // Tampilkan modal
    showModal();

    // Tutup semua dropdown ...
    document.querySelectorAll(".post-menu-dropdown").forEach((menu) => {
      menu.classList.add("hidden");
    });
  });

  // 2. Listener untuk tombol 'X' dan 'Batal' (tetap sama)
  closeButton.addEventListener("click", hideModal);
  cancelButton.addEventListener("click", hideModal);

  // 3. Listener untuk klik di luar area modal (tetap sama)
  reportModal.addEventListener("click", (e) => {
    if (e.target === reportModal) {
      hideModal();
    }
  });

  // 4. Listener untuk form submission (tetap sama)
  reportForm.addEventListener("submit", (e) => {
    e.preventDefault();
    // ... (Logika form submit Anda dari langkah sebelumnya SAMA PERSIS) ...
    const targetId = hiddenTargetId.value;
    const targetType = hiddenTargetType.value;
    const reasonRadio = reportForm.querySelector(
      'input[name="reason"]:checked'
    );

    if (!reasonRadio) {
      alert("Anda harus memilih satu alasan.");
      return;
    }
    const reason = reasonRadio.value;

    fetch(`${BASE_URL}/report/create`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        target_type: targetType,
        target_id: targetId,
        reason: reason,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Terima kasih, laporan Anda telah kami terima.");
          hideModal();

          if (lastClickedReportButton) {
            lastClickedReportButton.disabled = true;
            // Ubah teks di dalam tombol
            const textSpan =
              lastClickedReportButton.querySelector("span") ||
              lastClickedReportButton;
            textSpan.innerText = "Dilaporkan";
            // Ganti ikon
            lastClickedReportButton.querySelector(
              "svg"
            ).innerHTML = `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />`;
            lastClickedReportButton.classList.add("text-green-500");
            lastClickedReportButton.classList.remove("text-gray-700");
          }
        } else {
          alert("Gagal mengirim laporan: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Report error:", error);
        alert("Terjadi kesalahan saat mengirim laporan.");
      });
  });
});
