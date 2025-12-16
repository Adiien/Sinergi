document.addEventListener("DOMContentLoaded", () => {
  const notifBtn = document.getElementById("notification-btn");
  const notifDropdown = document.getElementById("notification-dropdown");
  const container = document.getElementById("notification-container");

  // Pastikan struktur HTML di navbar sesuai
  // Gunakan ID yang baru kita buat
  const notifListContainer = document.getElementById("notification-list");
  const notifBadge = document.createElement("span");

  // Setup Badge Style
  notifBadge.className =
    "absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-600 bg-red-500 hidden";
  if (notifBtn) notifBtn.appendChild(notifBadge);

  // Ambil BASE_URL dari global variable (biasanya diset di footer/header)
  const BASE_URL = window.BASE_URL || "";

  // --- FUNGSI 1: Mengatur Tampilan Notifikasi ---
  function renderNotifItem(notif) {
    const type = (notif.TYPE || "").toLowerCase();
    const actorName = notif.ACTOR_NAME || "Someone";
    const time = notif.FMT_TIME || "";
    const refId = notif.REFERENCE_ID || "";
    const notifId = notif.NOTIFICATION_ID;

    let message = "";
    let icon = "";
    let url = `${BASE_URL}/home`;
    let isInvite = false; // Penanda khusus invite

    // Cek Tipe Notifikasi
    if (type === "dm") {
      message = `<span class="font-bold">${actorName}</span> sent you a message.`;
      icon = `<div class="bg-indigo-100 p-1.5 rounded-full"><svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18" /></svg></div>`;
      url = `${BASE_URL}/messages/show?user_id=${notif.ACTOR_ID}`;
    } else if (type === "like") {
      message = `<span class="font-bold">${actorName}</span> liked your post.`;
      icon = `<div class="bg-blue-100 p-1.5 rounded-full"><svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></div>`;
    } else if (type === "comment") {
      message = `<span class="font-bold">${actorName}</span> commented on your post.`;
      icon = `<div class="bg-green-100 p-1.5 rounded-full"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18z"/></svg></div>`;
    } else if (type === "forum_invite") {
      // [BAGIAN PENTING] Jika tipe-nya invite, aktifkan mode tombol
      isInvite = true;
      message = `<span class="font-bold">${actorName}</span> invited you to join a group.`;
      icon = `<div class="bg-purple-100 p-1.5 rounded-full"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg></div>`;
      url = `${BASE_URL}/forum/show?id=${refId}`; // Link ke forum
    } else {
      message = "New notification";
      icon = `<div class="bg-gray-100 p-1.5 rounded-full"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg></div>`;
    }

    const bgClass = notif.IS_READ == 0 ? "bg-blue-50/50" : "bg-white";

    // [LOGIKA TAMPILAN]
    // Jika Invite: Tampilkan tombol Accept & Decline
    if (isInvite) {
      return `
            <div id="notif-item-${notifId}" class="flex flex-col px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition ${bgClass}">
                <div class="flex items-start space-x-3 cursor-pointer" onclick="window.location.href='${url}'">
                    <div class="flex-shrink-0 mt-1">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 break-words">${message}</p>
                        <p class="text-xs text-gray-400 mt-1">${time}</p>
                    </div>
                    ${
                      notif.IS_READ == 0
                        ? '<span class="w-2 h-2 bg-blue-600 rounded-full mt-2"></span>'
                        : ""
                    }
                </div>
                <div class="ml-10 mt-2 flex space-x-2">
                    <button onclick="handleInvite('${notifId}', '${refId}', 'accept')" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow-sm transition">
                        Accept
                    </button>
                    <button onclick="handleInvite('${notifId}', '${refId}', 'decline')" class="px-3 py-1 bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs font-bold rounded shadow-sm transition">
                        Decline
                    </button>
                </div>
            </div>
            `;
    }

    // Jika Bukan Invite: Tampilkan Link Biasa (<a>)
    return `
        <a href="${url}" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 ${bgClass}">
            <div class="flex-shrink-0 mt-1">${icon}</div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 break-words">${message}</p>
                <p class="text-xs text-gray-400 mt-1">${time}</p>
            </div>
            ${
              notif.IS_READ == 0
                ? '<span class="w-2 h-2 bg-blue-600 rounded-full mt-2"></span>'
                : ""
            }
        </a>
        `;
  }

  // --- FUNGSI 2: Fetch Data dari API ---
  async function loadNotifications() {
    if (!notifListContainer) return;

    try {
      const response = await fetch(`${BASE_URL}/api/notifications`);
      const data = await response.json();

      // Update Badge
      if (data.unread_count > 0) {
        notifBadge.classList.remove("hidden");
      } else {
        notifBadge.classList.add("hidden");
      }

      // Render List
      if (data.notifications.length > 0) {
        notifListContainer.innerHTML = "";
        // Gunakan class untuk scroll jika list panjang
        notifListContainer.className =
          "max-h-[300px] overflow-y-auto custom-scroll bg-white relative z-10";

        data.notifications.forEach((notif) => {
          notifListContainer.innerHTML += renderNotifItem(notif);
        });
      } else {
        notifListContainer.className =
          "bg-white relative z-10 min-h-[100px] flex flex-col items-center justify-center text-center p-6";
        notifListContainer.innerHTML = `
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <p class="text-gray-600 font-medium text-base">No new notifications</p>
                `;
      }
    } catch (error) {
      console.error("Gagal memuat notifikasi:", error);
    }
  }

  // --- FUNGSI 3: Tandai Sudah Dibaca ---
  function markAllAsRead() {
    fetch(`${BASE_URL}/api/notifications/read`).then(() => {
      notifBadge.classList.add("hidden");
    });
  }

  // --- Setup Event Listener ---
  if (notifBtn && notifDropdown) {
    loadNotifications();

    notifBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const isHidden = notifDropdown.classList.contains("hidden");

      if (isHidden) {
        notifDropdown.classList.remove("hidden");
        loadNotifications();
        markAllAsRead();
      } else {
        notifDropdown.classList.add("hidden");
      }
    });

    // Polling (cek notif baru setiap 3 detik)
    setInterval(loadNotifications, 3000);

    document.addEventListener("click", (e) => {
      if (container && !container.contains(e.target)) {
        notifDropdown.classList.add("hidden");
      }
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        notifDropdown.classList.add("hidden");
      }
    });
  }
});

// --- FUNGSI GLOBAL: Handle Invite (Harus di luar DOMContentLoaded) ---
window.handleInvite = function (notifId, forumId, action) {
  const formData = new FormData();
  formData.append("notif_id", notifId);

  // Jika Accept, kirim juga ID forumnya
  if (action === "accept") {
    formData.append("forum_id", forumId);
  }

  const endpoint =
    action === "accept" ? "api/invite/accept" : "api/invite/decline";
  const baseUrl = window.BASE_URL || "";

  // Efek visual loading
  const item = document.getElementById(`notif-item-${notifId}`);
  if (item) item.style.opacity = "0.5";

  fetch(`${baseUrl}/${endpoint}`, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        // Jika sukses, hapus notifikasi dari list
        if (item) item.remove();

        if (action === "accept") {
          // Redirect ke forum
          window.location.href = `${baseUrl}/forum/show?id=${forumId}`;
        }
      } else {
        alert("Gagal memproses permintaan.");
        if (item) item.style.opacity = "1";
      }
    })
    .catch((err) => {
      console.error(err);
      if (item) item.style.opacity = "1";
    });
};
