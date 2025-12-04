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
                    <button class="text-indigo-600 border-b-2 border-indigo-600 pb-1">All</button>
                    <button class="hover:text-gray-800 pb-1">Unread</button>
                    <button class="hover:text-gray-800 pb-1">Groups</button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scroll">
                <?php if (!empty($contacts)): ?>
                    <?php foreach ($contacts as $contact): ?>
                        <a href="<?= BASE_URL ?>/messages/show?user_id=<?= htmlspecialchars($contact['USER_ID']) ?>"
                           class="flex items-center px-5 py-4 hover:bg-gray-50 cursor-pointer border-l-4 border-transparent">
                            <div class="relative">
                                <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <?= strtoupper(substr($contact['NAMA'], 0, 1)) ?>
                                </div>
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
                                <p class="text-sm text-gray-500 truncate">
                                    <?= htmlspecialchars($contact['EMAIL']) ?>
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

        <!-- PANEL KANAN: EMPTY STATE -->
        <div class="col-span-1 lg:col-span-8 bg-white rounded-2xl shadow-lg flex flex-col h-full overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10">
                <h3 class="font-bold text-gray-900">Messages</h3>
                <span class="text-sm text-gray-400">
                    Pilih kontak di sebelah kiri untuk mulai percakapan
                </span>
            </div>

            <div class="flex-1 flex flex-col items-center justify-center text-center text-gray-400">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M7 8h10M7 12h6m-1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-700">Belum ada percakapan yang dipilih</p>
                <p class="text-sm text-gray-500 mt-1">
                    Klik salah satu pengguna di daftar Messages untuk mulai chat.
                </p>
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50 text-sm text-gray-400 text-center">
                Pilih penerima terlebih dahulu sebelum mengirim pesan.
            </div>
        </div>

    </div>
</main>

<script src="<?= BASE_URL ?>/public/assets/js/Notification.js"></script>
</body>
</html>
 