<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Messages - SINERGI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden pt-24 flex flex-col">

<?php require_once 'views/partials/navbar.php'; ?>

<main class="container mx-auto p-4 flex-1 h-full max-h-[calc(100vh-6rem)]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">

        <!-- SIDEBAR KONTAK -->
        <div class="hidden lg:flex lg:col-span-4 bg-white rounded-2xl shadow-lg flex-col h-full overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-3">
                        <a href="<?= BASE_URL ?>/home"
                           class="text-gray-600 hover:text-indigo-600 transition-colors p-1 rounded-full hover:bg-gray-100"
                           title="Back to Home">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h2 class="text-xl font-bold text-gray-800">Messages</h2>
                    </div>

                    <button class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Find people and groups"
                           class="w-full bg-gray-100 text-gray-700 rounded-full py-2 pl-10 pr-4
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 text-sm">
                </div>

                <div class="flex justify-between mt-4 text-sm font-medium text-gray-500">
    <button
        class="tab-btn text-indigo-600 border-b-2 border-indigo-600 pb-1"
        data-tab="all">
        All
    </button>
    <button
        class="tab-btn hover:text-gray-800 border-b-2 border-transparent pb-1"
        data-tab="unread">
        Unread
    </button>
    <button
        class="tab-btn hover:text-gray-800 border-b-2 border-transparent pb-1"
        data-tab="groups">
        Groups
    </button>
</div>

            </div>

            <div class="flex-1 overflow-y-auto custom-scroll">
                <?php if (!empty($contacts)): ?>
    <?php foreach ($contacts as $contact): ?>
        <?php
            // unread count dari query (alias: UNREAD_COUNT)
            $hasUnread = !empty($contact['UNREAD_COUNT']) && $contact['UNREAD_COUNT'] > 0;
        ?>
        <a href="<?= BASE_URL ?>/messages/show?user_id=<?= htmlspecialchars($contact['USER_ID']) ?>"
           class="flex items-center px-5 py-4 hover:bg-gray-50 cursor-pointer border-l-4
                  <?= $contact['USER_ID'] == $userData['USER_ID'] ? 'border-indigo-500 bg-gray-50' : 'border-transparent'; ?>"
           
           data-contact-item
           data-unread="<?= $hasUnread ? '1' : '0' ?>"
           data-type="direct">

            <div class="relative">
                <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    <?= strtoupper(substr($contact['NAMA'], 0, 1)) ?>
                </div>

                <?php if ($hasUnread): ?>
                    <!-- badge merah kecil di avatar -->
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full ring-2 ring-white"></span>
                <?php endif; ?>
            </div>

            <div class="ml-4 flex-1">
                <div class="flex justify-between items-baseline">
                    <h3 class="font-bold text-gray-900">
                        <?= htmlspecialchars($contact['NAMA']) ?>
                    </h3>
                    <span class="text-xs text-gray-400">
                        <?= htmlspecialchars($contact['ROLE_NAME']) ?>
                    </span>
                </div>

                <?php
                    // ==== PREVIEW LAST MESSAGE ====
                    $content = $contact['LAST_CONTENT'] ?? null;
                    if ($content instanceof OCILob) {
                        $content = $content->load();
                    }
                    if ($content !== null) {
                        $content = trim((string)$content);
                    }

                    $type    = isset($contact['LAST_MSG_TYPE']) ? strtolower($contact['LAST_MSG_TYPE']) : 'text';
                    $preview = '';

                    if ($type === 'image') {
                        $preview = '[Image]';
                        if ($content !== '') {
                            $preview .= ' ' . $content;
                        }
                    } else {
                        $preview = $content !== '' ? $content : '';
                    }

                    if ($preview === '') {
                        $preview = 'No messages yet';
                    }

                    $timeRaw = $contact['LAST_MESSAGE_AT'] ?? null;
                    $time    = $timeRaw ? (string)$timeRaw : '';
                ?>

                <p class="text-sm text-gray-500 truncate">
                    <?= htmlspecialchars($preview) ?>
                </p>
                <p class="text-[10px] text-gray-400">
                    <?= htmlspecialchars($time) ?>
                </p>
            </div>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <p class="px-5 py-4 text-sm text-gray-500">
        Belum ada pengguna lain yang bisa diajak chat.
    </p>
<?php endif; ?>

            </div>
        </div>

        <!-- PANEL CHAT KANAN -->
        <div class="col-span-1 lg:col-span-8 bg-white rounded-2xl shadow-lg flex flex-col h-full overflow-hidden">

            <!-- HEADER CHAT -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10">
                <div class="flex items-center space-x-4">
                    <a href="<?= BASE_URL ?>/messages" class="lg:hidden text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>

                    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                        <?= strtoupper(substr($userData['NAMA'], 0, 1)) ?>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">
                            <?= htmlspecialchars($userData['NAMA']) ?>
                        </h3>
                        <p class="text-xs text-gray-500">
                            <?= htmlspecialchars($userData['EMAIL']) ?>
                        </p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- ISI CHAT -->
            <div id="chat-box" class="flex-1 overflow-y-auto custom-scroll p-6 space-y-6 bg-white">
                <?php if (!empty($conversation)): ?>
                    <?php foreach ($conversation as $msg): ?>
                    <?php
                        $isMine = ($msg['SENDER_ID'] == $me);

                        // AMAN: pastikan CONTENT SELALU STRING
                        $contentRaw = $msg['CONTENT'];

                        if ($contentRaw instanceof OCILob) {
                            $contentText = $contentRaw->load();
                        } else {
                            $contentText = $contentRaw !== null ? (string)$contentRaw : '';
                        }

                            $timeRaw = isset($msg['CREATED_AT']) ? $msg['CREATED_AT'] : '';
                            $time    = $timeRaw !== null ? (string)$timeRaw : '';
                            // TAMBAHAN: deteksi image
                            $msgType  = isset($msg['MSG_TYPE']) ? strtolower((string)$msg['MSG_TYPE']) : 'text';
                            $filePath = $msg['FILE_PATH'] ?? null;
                            $isImage  = ($msgType === 'image' && !empty($filePath));

                            // URL gambar (kalau ada)
                            $imageUrl = $isImage
                            ? BASE_URL . '/public/uploads/messages/' . rawurlencode($filePath)
                            : null;
                    ?>


                        <?php if (!$isMine): ?>
                            <!-- pesan masuk -->
                            <div class="flex items-end space-x-3">
                                <div class="w-10 h-10 bg-indigo-500 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold">
                                    <?= strtoupper(substr($userData['NAMA'], 0, 1)) ?>
                                </div>
                                <div class="bg-[#eff3f8] text-gray-800 px-5 py-3 rounded-2xl rounded-bl-none shadow-sm">
                            <?php if ($isImage && $imageUrl): ?>
                                <img src="<?= htmlspecialchars($imageUrl) ?>"
                                    alt="Attachment"
                                    class="rounded-sm max-h-72 object-contain mb-2">
                            <?php if (trim($contentText) !== ''): ?>
                                <p class="text-xs text-gray-700 mt-2 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($contentText)) ?>
                                </p>
                            <?php endif; ?>
                            <?php else: ?>
                            <p class="text-sm leading-relaxed">
                                <?= nl2br(htmlspecialchars($contentText)) ?>
                            </p>
                            <?php endif; ?>
                        </div>

                            </div>
                        <?php else: ?>
                            <!-- pesan saya -->
                            <div class="flex items-end justify-end space-x-3">
                                <div class="max-w-[70%]">
                                    <div class="bg-[#36364c] text-white px-5 py-3 rounded-2xl rounded-br-none shadow-md">
                                <?php if ($isImage && $imageUrl): ?>
                                    <img src="<?= htmlspecialchars($imageUrl) ?>"
                                        alt="Attachment"
                                        class="rounded-sm max-h-72 object-contain mb-1 bg-black/30">
                                <?php if (trim($contentText) !== ''): ?>
                                    <p class="text-xs text-gray-100 mt-2 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($contentText)) ?>
                                    </p>
                                <?php endif; ?>
                                <?php else: ?>
                                    <p class="text-sm leading-relaxed">
                                        <?= nl2br(htmlspecialchars($contentText)) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                                    <div class="flex justify-end items-center mt-1 mr-1 space-x-1">
                                        <p class="text-[10px] text-gray-400">
                                            <?= htmlspecialchars($time) ?>
                                        </p>
                                        <?php if (isset($msg['IS_READ']) && $msg['IS_READ'] === 'Y'): ?>
                                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 13l4 4L19 7M5 13l4 4L19 7"></path>
                                            </svg>
                                            <svg class="w-3 h-3 text-blue-500 -ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="h-full flex items-center justify-center text-gray-400">
                        <p class="text-sm">Belum ada pesan. Mulai percakapan pertama kamu.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- INPUT PESAN -->
<div class="p-4 border-t border-gray-100 bg-white">
    <form id="send-form" enctype="multipart/form-data" method="POST" class="flex flex-col space-y-3">

        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($userData['USER_ID']) ?>">

        <!-- BARIS UTAMA: icon file, textbox, dan tombol kirim -->
        <div class="flex items-center space-x-3">
            <label class="text-gray-500 hover:text-gray-700 p-2 bg-gray-100 rounded-full cursor-pointer">
                <input type="file" name="attachment" id="attachment" class="hidden" accept="image/*">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </label>

            <div class="flex-1 relative">
                <input type="text" name="content" placeholder="Write a message"
                       class="w-full bg-[#eff3f8] text-gray-700 rounded-full py-3 px-5
                              focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <button type="submit" class="text-[#36364c] hover:text-indigo-800">
                <svg class="w-8 h-8 transform rotate-45" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                </svg>
            </button>
        </div>

        <!-- PREVIEW ATTACHMENT (ROW KEDUA) -->
        <div id="attachment-preview" class="hidden flex items-center space-x-3">

            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-200 flex items-center justify-center">
                <img id="attachment-preview-img"
                     src=""
                     class="max-w-full max-h-full object-cover hidden">
                <span id="attachment-preview-fallback"
                      class="text-xs text-gray-500">
                    No preview
                </span>
            </div>

            <div class="flex-1">
                <p id="attachment-preview-name" class="text-xs text-gray-700 truncate"></p>
                <p id="attachment-preview-size" class="text-[10px] text-gray-400"></p>
            </div>

            <button type="button"
                    id="attachment-preview-remove"
                    class="text-gray-400 hover:text-red-500 text-xs">
                Hapus
            </button>

        </div>

    </form>
</div>

        </div>
    </div>
</main>

<script>
    const BASE_URL    = "<?= BASE_URL ?>";
    const otherUserId = <?= (int)$userData['USER_ID'] ?>;
    const me          = <?= (int)$me ?>;
    const chatBox     = document.getElementById("chat-box");
    const sendForm    = document.getElementById("send-form");
    const contentInput = sendForm.querySelector('input[name="content"]');
    const attachmentInput        = document.getElementById("attachment");
    const previewContainer       = document.getElementById("attachment-preview");
    const previewImg             = document.getElementById("attachment-preview-img");
    const previewFallback        = document.getElementById("attachment-preview-fallback");
    const previewName            = document.getElementById("attachment-preview-name");
    const previewSize            = document.getElementById("attachment-preview-size");
    const previewRemoveButton    = document.getElementById("attachment-preview-remove");
    const tabButtons   = document.querySelectorAll(".tab-btn");
    const contactItems = document.querySelectorAll("[data-contact-item]");

    if (contentInput) {
        contentInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();

                if (typeof sendForm.requestSubmit === "function") {
                    sendForm.requestSubmit();
                } else {
                    sendForm.dispatchEvent(
                        new Event("submit", { cancelable: true, bubbles: true })
                    );
                }
            }
        });
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function setActiveTab(tab) {
    tabButtons.forEach(btn => {
        const isActive = btn.dataset.tab === tab;
        if (isActive) {
            btn.classList.add("text-indigo-600", "border-indigo-600");
            btn.classList.remove("hover:text-gray-800", "border-transparent");
        } else {
            btn.classList.remove("text-indigo-600", "border-indigo-600");
            btn.classList.add("hover:text-gray-800", "border-transparent");
        }
    });
    }
    
    function applyContactFilter(tab) {
    contactItems.forEach(item => {
        const unread = item.dataset.unread === "1";
        const type   = item.dataset.type || "direct";

        let show = true;
        if (tab === "unread") {
            show = unread;
        } else if (tab === "groups") {
            show = (type === "group");
        }

        if (show) {
            item.classList.remove("hidden");
        } else {
            item.classList.add("hidden");
        }
    });
    }

    setActiveTab("all");
    applyContactFilter("all");
    tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        const tab = btn.dataset.tab;
        setActiveTab(tab);
        applyContactFilter(tab);
    });
});


    function formatMessageBubble(msg) {
    const isMine   = Number(msg.SENDER_ID) === me;
    const rawContent = msg.CONTENT || "";
    const content  = escapeHtml(rawContent);
    const time     = escapeHtml(msg.CREATED_AT || "");

    const msgType  = (msg.MSG_TYPE || "text").toLowerCase();
    const filePath = msg.FILE_PATH || null;
    const isImage  = msgType === "image" && filePath;

    let innerHtml;

    if (isImage) {
        const imgUrl = `${BASE_URL}/public/uploads/messages/${encodeURIComponent(filePath)}`;
        // kalau ada caption (content tidak kosong)
        const captionHtml = content.trim()
            ? `<p class="text-xs mt-1">${content.replace(/\n/g, "<br>")}</p>`
            : "";

        innerHtml = `
            <img src="${imgUrl}"
                 alt="Attachment"
                 class="rounded-xl max-h-72 object-contain mb-1">
            ${captionHtml}
        `;
    } else {
        innerHtml = `
            <p class="text-base leading-relaxed">
                ${content.replace(/\n/g, "<br>")}
            </p>
        `;
    }

    if (isMine) {
        return `
            <div class="flex items-end justify-end space-x-3">
                <div class="max-w-[70%]">
                    <div class="bg-[#36364c] text-white px-5 py-3 rounded-2xl rounded-br-none shadow-md">
                        ${innerHtml}
                    </div>
                    <div class="flex justify-end items-center mt-1 mr-1">
                        <p class="text-[10px] text-gray-400">${time}</p>
                    </div>
                </div>
            </div>
        `;
    } else {
        return `
            <div class="flex items-end space-x-3">
                <div class="w-10 h-10 bg-indigo-500 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold">
                    <?= strtoupper(substr($userData['NAMA'], 0, 1)) ?>
                </div>
                <div class="max-w-[70%]">
                    <div class="bg-[#eff3f8] text-gray-800 px-5 py-3 rounded-2xl rounded-bl-none shadow-sm">
                        ${innerHtml}
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1 ml-1">${time}</p>
                </div>
            </div>
        `;
    }
}


    let lastRendered = "";

    async function refreshChat() {
        try {
            const res = await fetch(`${BASE_URL}/api/messages/fetch?user_id=${otherUserId}`, {
                cache: "no-store"
            });
            if (!res.ok) return;

            const data = await res.json();

            let html = "";
            if (Array.isArray(data) && data.length > 0) {
                html = data.map(formatMessageBubble).join("");
            } else {
                html = `
                    <div class="h-full flex items-center justify-center text-gray-400">
                        <p class="text-sm">Belum ada pesan. Mulai percakapan pertama kamu.</p>
                    </div>
                `;
            }

            if (html !== lastRendered) {
                chatBox.innerHTML = html;
                chatBox.scrollTop = chatBox.scrollHeight;
                lastRendered = html;
            }
        } catch (e) {
            console.error("Error refresh chat:", e);
        }
    }

    function humanFileSize(bytes) {
    if (!bytes || bytes <= 0) return "";
    const units = ["B","KB","MB","GB"];
    let i = 0;
    while (bytes >= 1024 && i < units.length - 1) {
        bytes /= 1024;
        i++;
    }
    return bytes.toFixed(1) + " " + units[i];
}

function clearAttachmentPreview() {
    previewContainer.classList.add("hidden");
    previewImg.src = "";
    previewImg.classList.add("hidden");
    previewFallback.classList.remove("hidden");
    previewName.textContent = "";
    previewSize.textContent = "";
    attachmentInput.value = "";
}

attachmentInput.addEventListener("change", function () {
    const file = attachmentInput.files[0];
    if (!file) {
        clearAttachmentPreview();
        return;
    }

    previewContainer.classList.remove("hidden");
    previewName.textContent = file.name;
    previewSize.textContent = humanFileSize(file.size);

    if (file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewImg.classList.remove("hidden");
            previewFallback.classList.add("hidden");
        };
        reader.readAsDataURL(file);
    } else {
        // Non-image file
        previewImg.classList.add("hidden");
        previewFallback.textContent = "Tidak dapat preview";
        previewFallback.classList.remove("hidden");
    }
});

previewRemoveButton.addEventListener("click", function(){
    clearAttachmentPreview();
});

    // submit handler
    sendForm.addEventListener("submit", async function(e) {
    e.preventDefault();
    console.log("Submit kepanggil");

    const formData = new FormData(sendForm);
    const content  = (formData.get("content") || "").trim();
    const file     = formData.get("attachment");

    if (!content && (!file || file.size === 0)) {
        console.log("Tidak ada content & file, batal kirim");
        return;
    }

    try {
        const res  = await fetch(`${BASE_URL}/messages/send`, {
            method: "POST",
            body: formData
        });

        const text = await res.text();
        console.log("Raw response:", text);

        let json;
        try {
            json = JSON.parse(text);
        } catch (e) {
            console.error("Response bukan JSON valid");
            return;
        }

        if (!json.success) {
            console.error("Send failed:", json.error || json);
            return;
        }

        sendForm.reset();
        refreshChat();
        clearAttachmentPreview();

    } catch (err) {
        console.error("JS error:", err);
    }
    });

    // panggil pertama dan pakai polling
    refreshChat();
    setInterval(refreshChat, 1000);
</script>


<script src="<?= BASE_URL ?>/public/assets/js/Notification.js"></script>
</body>
</html>
