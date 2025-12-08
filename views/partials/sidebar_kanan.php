<aside class="lg:col-span-1 sticky top-32 self-start">
    <div class="bg-white rounded-xl shadow-lg p-5">
        <h3 class="font-bold text-gray-900 text-lg mb-4">People to Follow</h3>

        <div class="space-y-4">
            <?php
            // Pastikan variable suggestedUsers ada dan tidak kosong
            if (isset($suggestedUsers) && !empty($suggestedUsers)):
                foreach ($suggestedUsers as $sUser):
                    // Ambil inisial nama untuk avatar
                    $initial = strtoupper(substr($sUser['NAMA'], 0, 1));

                    // Buat handle username (misal: @budi)
                    $handle = '@' . strtolower(str_replace(' ', '', $sUser['NAMA']));

                    // Warna background avatar acak
                    $bgColors = ['bg-blue-500', 'bg-purple-500', 'bg-yellow-500', 'bg-green-500', 'bg-pink-500', 'bg-indigo-500'];
                    $randomColor = $bgColors[array_rand($bgColors)];

                    // Cek Role User
                    $role = strtolower($sUser['ROLE_NAME'] ?? 'mahasiswa');

                    // Konfigurasi Badge Role
                    $badgeClass = 'hidden';
                    $roleLabel = '';

                    if ($role == 'admin') {
                        $badgeClass = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                        $roleLabel = 'Admin';
                    } elseif ($role == 'dosen') {
                        $badgeClass = 'bg-blue-100 text-blue-800 border border-blue-200';
                        $roleLabel = 'Dosen';
                    }
                    // Mahasiswa tidak perlu badge agar tidak terlalu ramai
            ?>
                    <div class="flex items-center justify-between gap-3 group">

                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <a href="<?= BASE_URL ?>/profile?id=<?= $sUser['USER_ID'] ?>" class="shrink-0 w-10 h-10 <?= $randomColor ?> rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm transition transform hover:scale-105">
                                <?= $initial ?>
                            </a>

                            <div class="min-w-0 flex flex-col">
                                <div class="flex items-center gap-1.5">
                                    <a href="<?= BASE_URL ?>/profile?id=<?= $sUser['USER_ID'] ?>" class="block font-semibold text-gray-900 text-sm truncate hover:text-blue-600 transition" title="<?= htmlspecialchars($sUser['NAMA']) ?>">
                                        <?= htmlspecialchars($sUser['NAMA']) ?>
                                    </a>

                                    <?php if ($roleLabel): ?>
                                        <span class="shrink-0 <?= $badgeClass ?> text-[10px] px-1.5 py-0.5 rounded-full font-medium leading-none">
                                            <?= $roleLabel ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <a href="<?= BASE_URL ?>/profile?id=<?= $sUser['USER_ID'] ?>" class="block text-xs text-gray-500 truncate hover:text-blue-500 transition">
                                    <?= $handle ?>
                                </a>
                            </div>
                        </div>

                        <button
                            class="follow-button shrink-0 border border-blue-600 text-blue-600 hover:bg-blue-50 px-3 py-1 rounded-full text-xs font-semibold transition-all duration-200"
                            data-user-id="<?= $sUser['USER_ID'] ?>">
                            Follow
                        </button>

                    </div>
                <?php
                endforeach;
            else:
                ?>
                <div class="text-center py-6">
                    <p class="text-sm text-gray-400">Tidak ada saran teman baru.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-5 pt-4 border-t border-gray-100 text-center">
            <a href="#" class="text-blue-600 font-semibold text-sm hover:text-blue-800 transition">See All Suggestions</a>
        </div>
    </div>
</aside>