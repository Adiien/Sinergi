document.addEventListener("DOMContentLoaded", () => {
  // ============================================================
  // 1. LOGIKA REPLY BARU (Template System)
  // ============================================================
  const template = document.getElementById("reply-form-template");

  // Event Delegation Tunggal untuk Body
  document.body.addEventListener("click", async (event) => {
    // --- A. Handle Tombol Reply (Versi Template / Baru) ---
    const replyButton = event.target.closest(".reply-button");
    const cancelButton = event.target.closest(".cancel-reply-button");

    if (replyButton && template) {
      event.preventDefault();

      // 1. Ambil data
      const parentId = replyButton.dataset.parentId;
      const postId = replyButton.dataset.postId;

      // 2. Tentukan target kontainer form
      const containerId = `reply-form-container-${parentId}`;
      const targetContainer = document.getElementById(containerId);

      if (!targetContainer) return;

      // 3. Hapus form balasan yang mungkin sudah ada di tempat lain
      document.querySelectorAll(".reply-form-active").forEach((form) => {
        // Opsional: Kembalikan ke template atau hapus saja karena kita clone dari template master
        form.remove();
      });

      // 4. Kloning template dan isi hidden input
      const replyForm = template.content.cloneNode(true).firstElementChild; // Gunakan .content jika tag <template>
      // Fallback jika bukan tag <template> tapi div biasa:
      // const replyForm = template.cloneNode(true);

      replyForm.id = ""; // Hapus ID agar unik
      replyForm.classList.remove("hidden");
      replyForm.classList.add("reply-form-active");

      // Isi nilai input tersembunyi
      const inputPostId = replyForm.querySelector('input[name="post_id"]');
      const inputParentId = replyForm.querySelector('input[name="parent_id"]');
      if (inputPostId) inputPostId.value = postId;
      if (inputParentId) inputParentId.value = parentId;

      // 5. Masukkan form baru ke dalam kontainer target
      targetContainer.innerHTML = ""; // Bersihkan kontainer dulu
      targetContainer.appendChild(replyForm);

      // 6. Fokus pada textarea
      const textarea = replyForm.querySelector("textarea");
      if (textarea) textarea.focus();

      return; // Selesai handling reply template
    }

    if (cancelButton) {
      event.preventDefault();
      // Hapus form aktif
      const activeForm = cancelButton.closest(".reply-form-active");
      if (activeForm) {
        activeForm.remove();
      }
      return;
    }

    // --- B. Handle Tombol Reply (Versi Lama / Fallback) ---
    // Digunakan jika HTML masih menggunakan class .reply-trigger dan form hidden statis
    const replyTrigger = event.target.closest(".reply-trigger");
    if (replyTrigger) {
      event.preventDefault();
      const commentId = replyTrigger.dataset.commentId;
      const postId = replyTrigger.dataset.postId;

      // Cari form reply spesifik (cara lama)
      const replyForm = document.getElementById(`reply-form-${commentId}`);
      if (replyForm) {
        if (replyForm.classList.contains("hidden")) {
          // Tutup semua form lain dulu (opsional, biar rapi)
          document
            .querySelectorAll('form[id^="reply-form-"]')
            .forEach((f) => f.classList.add("hidden"));

          replyForm.classList.remove("hidden");
          const input = replyForm.querySelector('input[name="content"]');
          if (input) input.focus();
        } else {
          replyForm.classList.add("hidden");
        }
      }
      return;
    }

    // --- C. Handle Like Comment ---
    const likeBtn = event.target.closest(".comment-like-btn");
    if (likeBtn) {
      event.preventDefault();
      const commentId = likeBtn.dataset.commentId;
      const countSpan = likeBtn.querySelector(".comment-like-count");
      const icon = likeBtn.querySelector("svg");

      try {
        const res = await fetch(
          `${window.BASE_URL}/post/comment/like?id=${commentId}`
        );
        const data = await res.json();

        if (data.success) {
          countSpan.innerText = data.count;
          if (data.isLiked) {
            likeBtn.classList.add("text-pink-500");
            likeBtn.classList.remove("text-gray-400");
            icon.setAttribute("fill", "currentColor");
          } else {
            likeBtn.classList.remove("text-pink-500");
            likeBtn.classList.add("text-gray-400");
            icon.setAttribute("fill", "none");
          }
        }
      } catch (err) {
        console.error("Error liking comment:", err);
      }
    }
  });

  // ============================================================
  // 3. HANDLE SUBMIT FORM (Main & Reply) - AJAX
  // ============================================================
  document.body.addEventListener("submit", async (event) => {
    const form = event.target;

    // Cek apakah ini form komentar (baik utama maupun balasan)
    if (
      form.matches('form[action*="/post/comment"]') ||
      form.classList.contains("reply-form-active")
    ) {
      event.preventDefault();

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn ? submitBtn.innerText : "Kirim";
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerText = "...";
      }

      try {
        const formData = new FormData(form);
        // Pastikan URL action benar. Jika form dari template, action mungkin kosong di HTML,
        // jadi kita set default atau ambil dari atribut action
        const actionUrl = form.action || `${window.BASE_URL}/post/comment`;

        const response = await fetch(`${actionUrl}?ajax=1`, {
          method: "POST",
          body: formData,
        });
        const data = await response.json();

        if (data.success) {
          form.reset();

          // --- Logika Penempatan HTML Baru ---
          const parentId = data.data.parent_id;
          let targetContainer;

          if (parentId) {
            // Jika ini balasan, masukkan ke container replies milik parent
            targetContainer = document.getElementById(
              `replies-container-${parentId}`
            );

            // Jika pakai sistem template, form balasan biasanya dihapus setelah submit sukses
            if (form.classList.contains("reply-form-active")) {
              form.remove();
            } else {
              // Cara lama: sembunyikan form
              form.classList.add("hidden");
            }
          } else {
            // Jika komentar utama, cari list utama berdasarkan post_id
            const postId = formData.get("post_id");
            targetContainer = document.getElementById(
              `comments-list-${postId}`
            );
          }

          if (targetContainer) {
            // Hapus pesan "Belum ada komentar" jika ada
            const emptyMsg = targetContainer.querySelector(".empty-msg");
            if (emptyMsg) emptyMsg.remove();

            const html = `
                         <div class="flex items-start space-x-2 animate-fade-in-up mt-3">
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold text-xs flex-shrink-0">
                                <span>${data.data.initial}</span>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-100 rounded-xl p-2 px-3 inline-block">
                                    <h4 class="text-xs font-bold text-gray-900">${data.data.nama}</h4>
                                    <p class="text-sm text-gray-800">${data.data.content}</p>
                                </div>
                                <div class="flex items-center space-x-3 mt-1 ml-1">
                                    <span class="text-xs text-gray-500">Just now</span>
                                </div>
                            </div>
                        </div>`;

            targetContainer.insertAdjacentHTML("beforeend", html);
          }
        } else {
          alert(data.message || "Gagal mengirim komentar.");
        }
      } catch (error) {
        console.error("Error submitting comment:", error);
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
        }
      }
    }
  });
});
