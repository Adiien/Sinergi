<aside class="lg:col-span-1 sticky top-32 self-start">
    <div class="bg-white rounded-xl shadow-lg p-5">
        <h3 class="font-bold text-gray-900 text-lg mb-4">People to Follow</h3>

        <div class="space-y-4">
            <?php 
            // Pastikan variable suggestedUsers ada
            if (isset($suggestedUsers) && !empty($suggestedUsers)): 
                foreach ($suggestedUsers as $sUser):
                    // Ambil inisial
                    $initial = strtoupper(substr($sUser['NAMA'], 0, 1));
                    // Buat handle
                    $handle = '@' . strtolower(str_replace(' ', '', $sUser['NAMA']));
                    
                    // Warna background acak
                    $bgColors = ['bg-blue-300', 'bg-purple-300', 'bg-yellow-300', 'bg-green-300', 'bg-pink-300'];
                    $randomColor = $bgColors[array_rand($bgColors)];
                    
                    // Cek Role
                    $role = strtolower($sUser['ROLE_NAME'] ?? 'mahasiswa');
                    
                    // Konfigurasi Badge
                    $badgeClass = 'hidden'; 
                    $roleLabel = '';
                    
                    if ($role == 'admin') {
                        $badgeClass = 'bg-indigo-100 text-indigo-800'; 
                        $roleLabel = 'Admin';
                    } elseif ($role == 'dosen') {
                        $badgeClass = 'bg-blue-100 text-blue-800'; 
                        $roleLabel = 'Dosen';
                    } elseif ($role == 'mahasiswa') {
                        $badgeClass = 'bg-green-100 text-green-800'; 
                        $roleLabel = 'Mahasiswa';
                    }
            ?>
                <div class="flex items-center justify-between gap-3">
                    
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="shrink-0 w-10 h-10 <?php echo $randomColor; ?> rounded-full flex items-center justify-center text-white font-bold text-sm">
                            <?php echo $initial; ?>
                        </div>
                        
                        <div class="min-w-0 flex flex-col">
                            <div class="flex items-center gap-1.5">
                                <h4 class="font-semibold text-gray-900 text-sm truncate" title="<?php echo htmlspecialchars($sUser['NAMA']); ?>">
                                    <?php echo htmlspecialchars($sUser['NAMA']); ?>
                                </h4>
                                
                                <?php if ($roleLabel): ?>
                                    <span class="shrink-0 <?php echo $badgeClass; ?> text-[10px] px-2 py-0.5 rounded-full font-medium inline-block">
                                        <?php echo $roleLabel; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-500 truncate"><?php echo $handle; ?></p>
                        </div>
                    </div>
                    
                    <button 
                        class="follow-button shrink-0 border border-blue-500 text-blue-500 hover:bg-blue-50 px-4 py-1 rounded-full text-xs font-medium transition-colors"
                        data-user-id="<?php echo $sUser['USER_ID']; ?>">
                        Follow
                    </button>

                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <p class="text-sm text-gray-500 text-center py-2">Tidak ada saran teman baru.</p>
            <?php endif; ?>
        </div>

        <div class="mt-5 pt-4 border-t">
            <a href="#" class="text-blue-600 font-medium text-sm hover:underline">See All</a>
        </div>
    </div>
</aside>