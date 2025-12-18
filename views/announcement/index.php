<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  
  <style>
    /* --- [Warna Latar Belakang & Font Global] --- */
    body { 
      background-color: #F3F4F6 !important; 
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
    }

    /* --- [Sembunyikan Scrollbar Utama] --- */
    html, body {
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* IE/Edge */
    }
    html::-webkit-scrollbar, body::-webkit-scrollbar {
      display: none; /* Chrome/Safari */
    }

    .btn-back-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background-color: white;
      color: #4a5568;
      text-decoration: none;
      border-radius: 15px;
      font-weight: 600;
      font-size: 14px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      transition: all 0.2s ease;
      border: 1px solid #eef2f6;
      margin-bottom: 24px;
    }

    .btn-back-home:hover {
      background-color: #f8fafc;
      transform: translateX(-5px);
      color: #540005;
    }
  </style>
</head>
<body>

  <?php require_once 'views/partials/navbar.php'; ?>

  <main id="main-content" class="container mx-auto pt-32 pb-20 px-4">
    <div class="max-w-3xl mx-auto">
      
      <a href="index.php" class="btn-back-home">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Beranda
      </a>    

      <div class="space-y-6">
        <?php if (empty($announcements)): ?>
          <div class="bg-white rounded-[2rem] shadow-sm p-10 text-center border border-gray-100">
            <p class="text-gray-400">Belum ada pengumuman yang diposting.</p>
          </div>
        <?php else: ?>
          <?php foreach ($announcements as $a): ?>
            <div class="bg-white rounded-[2rem] shadow-md border border-gray-100 overflow-hidden">
              <div class="p-8">
                <div class="flex justify-between items-start">
                  <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#54b405] rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-sm">
                      <?= strtoupper(substr($a['AUTHOR_NAME'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div>
                      <div class="flex items-center gap-2">
                        <h4 class="font-bold text-gray-900 text-lg leading-tight">
                          <?= htmlspecialchars($a['AUTHOR_NAME'] ?? 'Admin') ?>
                        </h4>
                        <span class="bg-[#dcfce7] text-[#166534] text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">
                          Admin
                        </span>
                      </div>  
                      <p class="text-xs text-gray-400 mt-1">
                        <?= date('M d, Y, g:i A', strtotime($a['CREATED_AT'])) ?> (Asia/Jakarta)
                      </p>
                    </div>
                  </div>
                </div>

                <div class="mt-8">
                  <h3 class="font-bold text-gray-900 text-xl mb-3 tracking-tight">
                    <?= htmlspecialchars($a['TITLE'] ?? 'Pengumuman') ?>
                  </h3>
                  <div class="text-gray-700 text-base leading-relaxed text-justify whitespace-pre-line">
                    <?= htmlspecialchars($a['CONTENT']) ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </main>

</body>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>

</html>