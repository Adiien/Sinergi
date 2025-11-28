document.addEventListener("DOMContentLoaded", () => {
    const template = document.getElementById('reply-form-template');
    if (!template) return;

    document.body.addEventListener('click', (event) => {
        const replyButton = event.target.closest('.reply-button');
        const cancelButton = event.target.closest('.cancel-reply-button');

        if (replyButton) {
            event.preventDefault();
            
            // 1. Ambil data
            const parentId = replyButton.dataset.parentId;
            const postId = replyButton.dataset.postId;
            
            // 2. Tentukan target kontainer form
            const containerId = `reply-form-container-${parentId}`;
            const targetContainer = document.getElementById(containerId);

            if (!targetContainer) return;

            // 3. Hapus form balasan yang mungkin sudah ada di tempat lain
            //    (Pastikan hanya satu form balasan yang aktif)
            document.querySelectorAll('.reply-form-active').forEach(form => {
                // Pindahkan kembali ke template sebelum menghapus kelas
                document.body.appendChild(form);
                form.classList.remove('reply-form-active');
            });

            // 4. Kloning template dan isi hidden input
            const replyForm = template.cloneNode(true);
            replyForm.id = ''; // Hapus ID template
            replyForm.classList.remove('hidden');
            replyForm.classList.add('reply-form-active');
            
            // Isi nilai input tersembunyi
            replyForm.querySelector('input[name="post_id"]').value = postId;
            replyForm.querySelector('input[name="parent_id"]').value = parentId;
            
            // 5. Masukkan form baru ke dalam kontainer target
            targetContainer.appendChild(replyForm);
            
            // 6. Fokus pada textarea
            replyForm.querySelector('textarea').focus();

        } else if (cancelButton) {
            event.preventDefault();
            // Pindahkan form aktif kembali ke template dan sembunyikan
            const activeForm = cancelButton.closest('.reply-form-active');
            if (activeForm) {
                document.body.appendChild(activeForm);
                activeForm.classList.add('hidden');
                activeForm.classList.remove('reply-form-active');
            }
        }
    });
});