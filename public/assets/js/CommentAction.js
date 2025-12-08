// Toggle Dropdown Menu
function toggleCommentMenu(commentId) {
  const menu = document.getElementById(`comment-menu-${commentId}`);
  // Tutup menu lain yang terbuka
  document.querySelectorAll('[id^="comment-menu-"]').forEach((el) => {
    if (el.id !== `comment-menu-${commentId}`) el.classList.add("hidden");
  });
  menu.classList.toggle("hidden");
}

// Hapus Komentar
function deleteComment(commentId) {
  if (!confirm("Hapus komentar ini?")) return;

  const formData = new FormData();
  formData.append("comment_id", commentId);

  fetch(`${window.BASE_URL}/post/comment/delete`, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        // [UPDATE] Refresh halaman otomatis setelah berhasil hapus
        location.reload();
      } else {
        alert(data.message || "Gagal menghapus komentar");
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Terjadi kesalahan koneksi.");
    });
}

// Tampilkan Form Edit
function editCommentUI(commentId) {
  // Sembunyikan menu
  document.getElementById(`comment-menu-${commentId}`).classList.add("hidden");

  // Sembunyikan teks asli
  document
    .getElementById(`comment-content-${commentId}`)
    .classList.add("hidden");

  // Tampilkan form edit
  const form = document.getElementById(`comment-edit-form-${commentId}`);
  form.classList.remove("hidden");

  // Isi textarea dengan teks terkini (bersihkan br tag jika perlu)
  const currentText = document.getElementById(
    `comment-content-${commentId}`
  ).innerText;
  form.querySelector("textarea").value = currentText;
}

// Batal Edit
function cancelEdit(commentId) {
  document
    .getElementById(`comment-edit-form-${commentId}`)
    .classList.add("hidden");
  document
    .getElementById(`comment-content-${commentId}`)
    .classList.remove("hidden");
}

// Simpan Edit
function saveEdit(commentId) {
  const form = document.getElementById(`comment-edit-form-${commentId}`);
  const newContent = form.querySelector("textarea").value;

  const formData = new FormData();
  formData.append("comment_id", commentId);
  formData.append("content", newContent);

  const btn = form.querySelector("button:last-child"); // Tombol simpan
  const originalText = btn.innerText;
  btn.innerText = "...";
  btn.disabled = true;

  fetch(`${window.BASE_URL}/post/comment/update`, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        // Update tampilan teks
        const contentP = document.getElementById(
          `comment-content-${commentId}`
        );
        contentP.innerHTML = data.new_content; // Update HTML dengan nl2br dari server

        // Reset UI
        cancelEdit(commentId);
      } else {
        alert(data.message || "Gagal mengupdate komentar");
      }
    })
    .catch((err) => console.error(err))
    .finally(() => {
      btn.innerText = originalText;
      btn.disabled = false;
    });
}

// Tutup menu jika klik di luar
document.addEventListener("click", function (e) {
  if (!e.target.closest('[onclick^="toggleCommentMenu"]')) {
    document.querySelectorAll('[id^="comment-menu-"]').forEach((el) => {
      el.classList.add("hidden");
    });
  }
});
