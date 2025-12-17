<div class="bg-white rounded-xl shadow p-4 space-y-2 sticky top-24">
  
  <a href="<?= BASE_URL ?>/admin?tab=users"
     class="block px-4 py-2 rounded transition-colors duration-200 
     <?= $tab === 'users' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
     Manajemen Pengguna
  </a>

  <a href="<?= BASE_URL ?>/admin?tab=stats"
     class="block px-4 py-2 rounded transition-colors duration-200
     <?= $tab === 'stats' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
     Overview Report
  </a>

  <a href="<?= BASE_URL ?>/admin?tab=reports"
     class="flex justify-between items-center px-4 py-2 rounded transition-colors duration-200
     <?= $tab === 'reports' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
     
     <span>Laporan Masuk</span>
     
     <span id="report-badge" class="hidden bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
        0
     </span>
  </a>

  <a href="<?= BASE_URL ?>/admin?tab=announcements"
   class="block px-4 py-2 rounded transition-colors duration-200
   <?= $tab === 'announcements' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' ?>">
   Pengumuman
    </a>


</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const badge = document.getElementById('report-badge');
    
    // Fungsi untuk cek server
    const checkReports = async () => {
        try {
            const res = await fetch('<?= BASE_URL ?>/api/admin/notifications');
            const data = await res.json();
            
            if (data.count > 0) {
                badge.innerText = data.count;     // Update angka
                badge.classList.remove('hidden'); // Munculkan badge
            } else {
                badge.classList.add('hidden');    // Sembunyikan jika 0
            }
        } catch (error) {
            console.error("Gagal mengambil notifikasi admin:", error);
        }
    };

    // 1. Cek saat halaman pertama kali dimuat
    checkReports();

    // 2. Cek ulang setiap 5 detik (Polling)
    setInterval(checkReports, 2000);
});
</script>