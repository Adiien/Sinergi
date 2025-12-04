  document.addEventListener("DOMContentLoaded", () => {
    const notifBtn = document.getElementById("notification-btn");
    const notifDropdown = document.getElementById("notification-dropdown");
    const container = document.getElementById("notification-container"); // Wrapper di navbar

    // Ambil elemen di dalam dropdown untuk diisi konten
    // Pastikan struktur HTML di navbar.php sesuai (lihat penjelasan di bawah)
    const notifListContainer = notifDropdown.querySelector(
      ".bg-white.relative.z-10.min-h-\\[100px\\]"
    );
    const notifBadge = document.createElement("span"); // Badge merah untuk jumlah unread

    // Setup Badge Style
    notifBadge.className =
      "absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-600 bg-red-500 hidden";
    if (notifBtn) notifBtn.appendChild(notifBadge);

    const BASE_URL = window.BASE_URL || "";

    // Fungsi Render Item Notifikasi
    function renderNotifItem(notif) {
  const type      = (notif.TYPE || "").toLowerCase();
  const actorName = notif.ACTOR_NAME || "Someone";
  const time      = notif.FMT_TIME || "";

  let message = "";
  let icon    = "";
  let url     = `${BASE_URL}/home`; // default untuk like/comment lama

  if (type === "dm") {
    message = `<span class="font-bold">${actorName}</span> sent you a message.`;
    icon = `
      <div class="bg-indigo-100 p-1.5 rounded-full">
        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18" />
        </svg>
      </div>`;
    url = `${BASE_URL}/messages/show?user_id=${notif.ACTOR_ID}`;
  } else if (type === "like") {
    message = `<span class="font-bold">${actorName}</span> liked your post.`;
    icon = `
      <div class="bg-blue-100 p-1.5 rounded-full">
        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 
                   12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 
                   3.41.81 4.5 2.09C13.09 3.81 14.76 3 
                   16.5 3 19.58 3 22 5.42 22 8.5c0 
                   3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
      </div>`;
  } else if (type === "comment") {
    message = `<span class="font-bold">${actorName}</span> commented on your post.`;
    icon = `
      <div class="bg-green-100 p-1.5 rounded-full">
        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
          <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 
                   .9-2 2v12c0 1.1.9 2 2 2h14l4 
                   4-.01-18z"/>
        </svg>
      </div>`;
  } else {
    // fallback biar gak kosong kaya screenshotmu tadi
    message = "New notification";
    icon = `
      <div class="bg-gray-100 p-1.5 rounded-full">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 
                   10 10 0 000-20z" />
        </svg>
      </div>`;
  }

  const bgClass = notif.IS_READ == 0 ? "bg-blue-50/50" : "bg-white";

  return `
    <a href="${url}" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 ${bgClass}">
      <div class="flex-shrink-0 mt-1">
         ${icon}
      </div>
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

    // Fungsi Fetch Data dari API
    async function loadNotifications() {
      try {
        const response = await fetch(`${BASE_URL}/api/notifications`);
        const data = await response.json();

        // 1. Update Badge
        if (data.unread_count > 0) {
          notifBadge.classList.remove("hidden");
        } else {
          notifBadge.classList.add("hidden");
        }

        // 2. Render List
        if (data.notifications.length > 0) {
          // Kosongkan container default ("No new notifications")
          // Kita ganti style container agar list bisa scroll kalau panjang
          notifListContainer.innerHTML = "";
          notifListContainer.className =
            "max-h-[300px] overflow-y-auto custom-scroll";

          data.notifications.forEach((notif) => {
            notifListContainer.innerHTML += renderNotifItem(notif);
          });
        } else {
          // Tampilkan state kosong default
          notifListContainer.className =
            "bg-white relative z-10 min-h-[100px] flex flex-col items-center justify-center text-center p-6";
          notifListContainer.innerHTML = `
              <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
              </div>
              <p class="text-gray-600 font-medium text-base">No new notifications</p>
          `;
        }
      } catch (error) {
        console.error("Gagal memuat notifikasi:", error);
      }
    }

    // Fungsi Tandai Sudah Dibaca
    function markAllAsRead() {
      fetch(`${BASE_URL}/api/notifications/read`).then(() => {
        notifBadge.classList.add("hidden");
        // Hapus background biru pada item (opsional, karena user mungkin tidak langsung melihat perubahan)
      });
    }

    if (notifBtn && notifDropdown) {
      // Load pertama kali saat halaman dibuka
      loadNotifications();

      // Toggle Dropdown
      notifBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const isHidden = notifDropdown.classList.contains("hidden");

        if (isHidden) {
          notifDropdown.classList.remove("hidden");
          // Load ulang data terbaru saat dibuka
          loadNotifications();
          // Tandai sudah dibaca
          markAllAsRead();
        } else {
          notifDropdown.classList.add("hidden");
        }
      });

      // Polling setiap 10 detik untuk cek notif baru (Realtime sederhana)
      setInterval(loadNotifications, 3000);

      document.addEventListener("click", (e) => {
        if (!container.contains(e.target)) {
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
