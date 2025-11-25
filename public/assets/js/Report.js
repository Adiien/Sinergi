document.addEventListener("DOMContentLoaded", () => {
  const BASE_URL = window.BASE_URL || "";

  const reportModal = document.getElementById("report-modal");
  const closeButton = document.getElementById("close-report-modal");
  const cancelButton = document.getElementById("cancel-report-button");
  const reportForm = document.getElementById("report-form");
  const hiddenTargetId = document.getElementById("report-target-id");
  const hiddenTargetType = document.getElementById("report-target-type");

  // --- ELEMEN BARU ---
  const otherContainer = document.getElementById("other-reason-container");
  const otherInput = document.getElementById("other-reason-text");
  const reasonRadios = document.querySelectorAll('input[name="reason"]');

  let lastClickedReportButton = null;

  if (!reportModal || !reportForm) return;

  function showModal() {
    reportModal.classList.remove("hidden");
    reportForm.reset();
    // Reset tampilan textarea saat modal dibuka ulang
    if (otherContainer) otherContainer.classList.add("hidden");
  }

  function hideModal() {
    reportModal.classList.add("hidden");
  }

  // --- LOGIKA TOGGLE "LAINNYA" ---
  reasonRadios.forEach((radio) => {
    radio.addEventListener("change", (e) => {
      if (e.target.value === "Lainnya") {
        otherContainer.classList.remove("hidden");
        otherInput.focus(); // Otomatis fokus ke textarea
      } else {
        otherContainer.classList.add("hidden");
      }
    });
  });

  // Event Delegation untuk tombol Report
  document.body.addEventListener("click", function (event) {
    const reportButton = event.target.closest(".report-button");
    if (!reportButton) return;

    const targetType = reportButton.dataset.targetType;
    const targetId = reportButton.dataset.targetId;

    if (!targetType || !targetId) return;

    hiddenTargetId.value = targetId;
    hiddenTargetType.value = targetType;
    lastClickedReportButton = reportButton;

    showModal();

    // Tutup menu dropdown lain agar rapi
    document.querySelectorAll(".post-menu-dropdown").forEach((menu) => {
      menu.classList.add("hidden");
    });
  });

  closeButton.addEventListener("click", hideModal);
  cancelButton.addEventListener("click", hideModal);

  reportModal.addEventListener("click", (e) => {
    if (e.target === reportModal) hideModal();
  });

  // --- UPDATE LOGIKA SUBMIT ---
  reportForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const targetId = hiddenTargetId.value;
    const targetType = hiddenTargetType.value;
    const reasonRadio = reportForm.querySelector(
      'input[name="reason"]:checked'
    );

    if (!reasonRadio) {
      alert("Anda harus memilih satu alasan.");
      return;
    }

    let finalReason = reasonRadio.value;

    // Jika user memilih "Lainnya", ambil isi textarea
    if (finalReason === "Lainnya") {
      const customText = otherInput.value.trim();
      if (!customText) {
        alert("Silakan tulis alasan laporan Anda.");
        otherInput.focus();
        return;
      }
      // Gabungkan agar admin tahu ini input manual
      finalReason = "Lainnya: " + customText;
    }

    fetch(`${BASE_URL}/report/create`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        target_type: targetType,
        target_id: targetId,
        reason: finalReason,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Terima kasih, laporan Anda telah kami terima.");
          hideModal();

          if (lastClickedReportButton) {
            lastClickedReportButton.disabled = true;
            lastClickedReportButton.innerHTML = "";

            const newIcon = document.createElement("span");
            newIcon.innerHTML = `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>`;

            const newText = document.createTextNode("Dilaporkan");

            lastClickedReportButton.appendChild(newIcon.firstChild);
            lastClickedReportButton.appendChild(newText);

            lastClickedReportButton.classList.remove(
              "text-gray-700",
              "hover:bg-gray-100"
            );
            lastClickedReportButton.classList.add(
              "text-green-600",
              "cursor-not-allowed"
            );
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
