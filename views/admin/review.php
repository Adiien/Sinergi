<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Laporan - Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center gap-4">
            <a href="<?= BASE_URL ?>/admin?tab=reports" class="text-gray-500 hover:text-gray-900 flex items-center gap-1">
                &larr; Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Review Laporan #<?= $report['REPORT_ID'] ?></h1>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white p-5 rounded-xl shadow">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Pelapor</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            <?= substr($report['REPORTER_NAME'] ?? 'U', 0, 1) ?>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($report['REPORTER_NAME']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($report['REPORTER_EMAIL']) ?></p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Dilaporkan pada: <?= $report['CREATED_AT'] ?></p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Alasan Pelaporan</h3>
                    <p class="text-gray-800 font-medium italic">"<?= htmlspecialchars($report['REASON']) ?>"</p>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Konten yang Dilaporkan (<?= ucfirst($report['TARGET_TYPE']) ?>)</h3>

                    <div class="flex items-center gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
                        <span class="text-xs text-gray-500">Diposting oleh:</span>
                        <span class="font-semibold text-gray-700"><?= htmlspecialchars($report['TARGET_USER_NAME']) ?></span>
                        <span class="text-xs text-gray-400">(<?= htmlspecialchars($report['TARGET_USER_EMAIL']) ?>)</span>
                    </div>

                    <div class="border rounded-lg p-4 bg-gray-50">
                        <?php if ($report['TARGET_TYPE'] === 'post'): ?>
                            <p class="text-gray-800 whitespace-pre-line mb-4"><?= htmlspecialchars($report['POST_CONTENT']) ?></p>
                            
                            <?php if (!empty($report['POST_IMAGE'])): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($report['POST_IMAGE']) ?>" 
                                     class="max-h-64 rounded border mx-auto" alt="Bukti Gambar">
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-500 italic">Konten tipe <?= $report['TARGET_TYPE'] ?> belum didukung preview.</p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-8 pt-6 border-t flex flex-wrap gap-4 justify-end">
                        
                        <form method="POST" onsubmit="return confirm('Yakin ingin mengabaikan laporan ini? Laporan akan ditandai selesai tanpa menghapus konten.')">
                            <input type="hidden" name="action" value="dismiss">
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">
                                ✕ Abaikan Laporan
                            </button>
                        </form>

                        <form method="POST" onsubmit="return confirm('PERINGATAN: Konten akan dihapus PERMANEN dari database. Lanjutkan?')">
                            <input type="hidden" name="action" value="delete_content">
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Hapus Konten & Selesaikan
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>