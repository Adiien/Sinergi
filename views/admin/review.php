<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Laporan - Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 min-h-screen py-8">

<div class="max-w-6xl mx-auto px-4">

    <!-- Back -->
    <a href="<?= BASE_URL ?>/admin?tab=reports"
       class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
        ← Kembali
    </a>

    <!-- Title -->
    <h1 class="text-xl font-semibold text-gray-800 mb-6">
        Detail Review Laporan #<?= $report['REPORT_ID'] ?>
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COLUMN -->
        <div class="space-y-6">

            <!-- Pelapor -->
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="text-xs font-semibold text-gray-400 uppercase mb-3">
                    Pelapor
                </h3>

                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                        <?= substr($report['REPORTER_NAME'] ?? 'U', 0, 1) ?>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">
                            <?= htmlspecialchars($report['REPORTER_NAME']) ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            <?= htmlspecialchars($report['REPORTER_EMAIL']) ?>
                        </p>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Dilaporkan pada <?= date('d M Y H:i', strtotime($report['CREATED_AT'])) ?>
                </p>
            </div>

            <!-- Alasan -->
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">
                    Alasan Pelaporan
                </h3>
                <p class="italic text-gray-800">
                    "<?= htmlspecialchars($report['REASON']) ?>"
                </p>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-xl shadow p-6">

                <h3 class="text-xs font-semibold text-gray-400 uppercase mb-4">
                    Konten yang Dilaporkan (<?= ucfirst($report['TARGET_TYPE']) ?>)
                </h3>

                <!-- Author -->
                <div class="bg-slate-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600">
                        Diposting oleh:
                        <span class="font-semibold text-gray-800">
                            <?= htmlspecialchars($report['TARGET_USER_NAME']) ?>
                        </span>
                        <span class="text-xs text-gray-500">
                            (<?= htmlspecialchars($report['TARGET_USER_EMAIL']) ?>)
                        </span>
                    </p>
                </div>

                <!-- Post Content -->
                <div class="border rounded-lg p-4">
                    <p class="text-gray-800 whitespace-pre-line">
                        <?= htmlspecialchars($report['POST_CONTENT']) ?>
                    </p>

                    <?php if (!empty($report['POST_IMAGE'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($report['POST_IMAGE']) ?>"
                             class="mt-4 max-h-72 rounded-lg border mx-auto">
                    <?php endif; ?>
                </div>

                <!-- ACTIONS -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">

                    <form method="POST"
                          onsubmit="return confirm('Abaikan laporan ini?')">
                        <input type="hidden" name="action" value="dismiss">
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            ✕ Abaikan Laporan
                        </button>
                    </form>

                    <form method="POST"
                          onsubmit="return confirm('Konten akan dihapus permanen. Lanjutkan?')">
                        <input type="hidden" name="action" value="delete_content">
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            🗑 Hapus Konten & Selesaikan
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
</body>

</html>