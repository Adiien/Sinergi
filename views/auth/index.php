<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SINERGI</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    .section_fade {
      transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
    }

    #main-section {
      transition: filter 0.5s ease-in-out
    }
  </style>
</head>

<body class="h-screen overflow-hidden">
  <?php if (isset($_SESSION['error_message'])): ?>
    <div id="alert-box" class="fixed top-20 right-5 bg-red-500 text-white p-4 rounded-lg shadow-lg z-[100]">
      <?php echo $_SESSION['error_message']; ?>
      <?php unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan 
      ?>
    </div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success_message'])): ?>
    <div id="alert-box" class="fixed top-20 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg z-[100]">
      <?php echo $_SESSION['success_message']; ?>
      <?php unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan 
      ?>
    </div>
  <?php endif; ?>

  <script>
    setTimeout(function() {
      const alertBox = document.getElementById('alert-box');
      if (alertBox) {
        // Buat transisi fade out
        alertBox.style.transition = 'opacity 0.5s ease-out';
        alertBox.style.opacity = '0';
        // Hapus elemen setelah transisi selesai
        setTimeout(() => alertBox.remove(), 500);
      }
    }, 3000); // 3000 milidetik = 3 detik
  </script>
  <nav id="main-nav" class="bg-gray-600 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
      <a href="#" class="flex items-center space-x-1">
        <div class="p-1.5">
          <img src="<?= BASE_URL ?>/public/assets/image/LOGOSINERGIBORDER.png" alt="Logo" class="w-10 h-10" />
        </div>
        <span class="text-white text-xl tracking-widest font-azeret">SINERGI</span>
      </a>
      <div class="hidden md:flex items-center space-x-8 mr-8">
        <div class="flex items-center space-x-4">
          <a id="login-button-nav" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
            Login
          </a>
          <a id="register-button-nav" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
            Registrasi
          </a>
        </div>
      </div>
      <div class="md:hidden">
        <button id="menu-button" class="text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
          </svg>
        </button>
      </div>
    </div>
  </nav>
  <!-- Hero Section -->
  <section id="hero-section" class="bg-[#ffffff] h-screen ">
    <div class="flex flex-col md:flex-row items-center h-full">
      <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0 px-6 md:px-12">
        <h1 class="text-4xl md:text-5xl font-extrabold text-[#36344B] leading-tight mb-6">
          Judul Besar yang Menarik Perhatian Pengunjung
        </h1>
        <p class="text-lg text-gray-300 mb-8">
          Deskripsi singkat atau slogan yang menjelaskan nilai utama atau tujuan dari situs web Anda.
        </p>
      </div>
      <div class="md:w-7/12 flex justify-end items-center relative mt-10 md:mt-0 -mr-6 md:-mr-12 lg:-mr-24">
        <img src="<?= BASE_URL ?>/public/assets/vector.png" alt="Hero Image"
          class="w-[130%] md:w-[150%] max-w-none h-auto object-contain ...">
      </div>
    </div>
  </section>
  <!-- Register Section -->
  <section id="register-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50">
    <h2 class="text-3xl font-bold text-center text-[#ffffff] mb-8">Registrasi</h2>
    <div class="bg-white p-4 rounded-xl shadow-2xl w-full max-w-2xl">
      <div class="flex items-center justify-center space-x-2 mb-4">
        <button id="btn-dosen" type="button" class="text-gray-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
          Dosen
        </button>
        <button id="btn-mahasiswa" type="button" class="bg-[#5e5e8f] text-white font-semibold px-6 py-3 rounded-lg shadow-md">
          Mahasiswa
        </button>
        <button id="btn-alumni" type="button" class="text-gray-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
          Alumni
        </button>
      </div>
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Create an account</h2>
      <form action="<?= BASE_URL ?>/auth/register" method="POST">
        <input type="hidden" id="role_name" name="role_name" value="mahasiswa">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 w-11/12 mx-auto">

          <div class="relative">
            <input type="text" id="nim-nip-input" name="nim-nip-input" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIM" required>
          </div>

          <div class="relative">
            <input type="text" name="nama" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama Lengkap" required>
          </div>

          <div class="relative">
            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Email : nama@stu.pnj.ac.id" required>
          </div>

          <div class="relative">
            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Password" required>
          </div>

          <div id="study-program-field" class="relative transition-all duration-300">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Study Program</label>
            <select id="program_studi" name="program_studi" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
              <option value="" disabled selected>Pilih program studi</option>
              <option value="Teknik Informatika">Teknik Informatika</option>
              <option value="Teknik Multimedia dan Jaringan">Teknik Multimedia dan Jaringan</option>
              <option value="Teknik Multimedia dan Desain">Teknik Multimedia dan Desain</option>
              <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan</option>
            </select>
          </div>

          <div id="admission-date-field" class="relative transition-all duration-300">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Tahun Masuk / Angkatan</label>
            <div class="relative">
              <select name="admission_year" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <?php
                $currentYear = date("Y");
                // Tampilkan 20 tahun ke belakang
                for ($i = $currentYear; $i >= $currentYear - 20; $i--) {
                  echo "<option value='$i'>$i</option>";
                }
                ?>
              </select>
            </div>
          </div>

        </div>

        <button type="submit" class="w-11/12 block mx-auto bg-[#5e5e8f] text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-800 transition duration-300 mt-8">
          Create Account
        </button>
      </form>

      <p class="text-center text-gray-600 mt-6">
        Already have an account?
        <a href="#" class="text-indigo-600 hover:underline font-medium" id="show-login-from-register">Login</a>
      </p>
    </div>
  </section>
  <!-- Login Section-->
  <section id="login-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50">
    <h2 class="text-3xl font-bold text-center text-[#ffffff] mb-8">Login</h2>

    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-md relative">
      <div class="flex flex-col items-center mb-6">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center border border-gray-300 mb-2">
          <img src="<?= BASE_URL ?>/public/assets/image/logo.png" alt="Logo" class="w-8 h-8" />
        </div>
        <h2 class="text-3xl font-bold tracking-[0.2em] text-gray-800">SINERGI</h2>
      </div>

      <?php
      // Logika Captcha (Tetap sama)
      $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
      $captcha_string = substr(str_shuffle($karakter), 0, 5);
      $_SESSION['captcha_string'] = $captcha_string;
      ?>

      <form action="<?= BASE_URL ?>/auth/login" method="POST">
        <div class="flex flex-col gap-y-4 w-11/12 mx-auto">

          <div class="relative">
            <input type="text" id="identifier" name="identifier" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Email / NIM / NIP" required>
          </div>

          <div class="relative">
            <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Password" required>
          </div>

          <div class="relative bg-gray-50 p-3 rounded-lg border border-gray-200">
            <label for="captcha" class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide text-center">
              Security Check
            </label>

            <div class="flex items-center gap-2">
              <div class="w-1/3 px-2 py-2 border border-gray-300 bg-gray-200 text-center rounded-lg select-none">
                <strong class="text-lg tracking-widest font-mono text-gray-700 decoration-dashed">
                  <?php echo $captcha_string; ?>
                </strong>
              </div>

              <input type="text" id="captcha" name="captcha"
                class="w-2/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Tulis kode di samping" required autocomplete="off" maxlength="5">
            </div>
          </div>

          <div class="text-right">
            <a href="<?= BASE_URL ?>/auth/forgot" class="text-xs font-semibold text-indigo-600 hover:underline">Forgot Password?</a>
          </div>

        </div>

        <button type="submit" class="w-11/12 block mx-auto bg-[#5e5e8f] text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-800 transition duration-300 mt-6 shadow-md">
          Login
        </button>
      </form>

      <p class="text-center text-sm text-gray-600 mt-6">
        Don't have an account?
        <a href="#" class="text-indigo-600 hover:underline font-bold" id="show-register-from-login">Register</a>
      </p>
    </div>
  </section>

  <section id="forgot-password-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md relative mx-4">
      <button onclick="window.location.href='<?= BASE_URL ?>'" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-[#36344B]">Lupa Password?</h2>
        <p class="text-sm text-gray-500 mt-2">Masukkan email Anda untuk menerima kode verifikasi.</p>
      </div>

      <form action="<?= BASE_URL ?>/auth/forgot" method="POST">
        <div class="mb-6">
          <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5e5e8f] focus:outline-none" placeholder="Email address" required>
        </div>
        <button type="submit" class="w-full bg-[#5e5e8f] text-white font-bold py-3 rounded-lg hover:bg-[#4a4a75] transition shadow-md">
          Kirim Kode
        </button>
      </form>

      <div class="text-center mt-6">
        <a href="#" id="back-to-login-from-forgot" class="text-sm text-indigo-600 font-semibold hover:underline">Kembali ke Login</a>
      </div>
    </div>
  </section>

  <section id="verify-code-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md relative mx-4">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-[#36344B]">Verifikasi</h2>
        <p class="text-sm text-gray-500 mt-2">Masukkan 4 digit kode yang dikirim ke email Anda.</p>
      </div>

      <form action="<?= BASE_URL ?>/auth/verify-code" method="POST">
        <div class="flex justify-center gap-4 mb-8">
          <?php for ($i = 0; $i < 4; $i++): ?>
            <input type="text" name="code[]" maxlength="1"
              class="w-14 h-14 border border-gray-300 rounded-lg text-center text-2xl font-bold focus:ring-2 focus:ring-[#5e5e8f] focus:outline-none otp-input" required autocomplete="off">
          <?php endfor; ?>
        </div>

        <div class="flex gap-3">
          <button type="button" onclick="location.href='<?= BASE_URL ?>'" class="flex-1 border border-gray-300 text-gray-600 font-bold py-3 rounded-lg hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-[#5e5e8f] text-white font-bold py-3 rounded-lg hover:bg-[#4a4a75] transition shadow-md">Verifikasi</button>
        </div>
      </form>
    </div>
  </section>

  <section id="reset-password-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md relative mx-4">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-[#36344B]">Password Baru</h2>
        <p class="text-sm text-gray-500 mt-2">Buat password baru untuk akun Anda.</p>
      </div>

      <form action="<?= BASE_URL ?>/auth/reset-password" method="POST">
        <div class="mb-4">
          <label class="block text-xs font-bold text-gray-700 mb-1">Password Baru</label>
          <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5e5e8f] focus:outline-none" placeholder="******" required>
        </div>

        <div class="mb-8">
          <label class="block text-xs font-bold text-gray-700 mb-1">Konfirmasi Password</label>
          <input type="password" name="confirm_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5e5e8f] focus:outline-none" placeholder="******" required>
        </div>

        <button type="submit" class="w-full bg-[#5e5e8f] text-white font-bold py-3 rounded-lg hover:bg-[#4a4a75] transition shadow-md">
          Simpan Password
        </button>
      </form>
    </div>
  </section>

  <script src="<?= BASE_URL ?>/public/assets/js/register.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/transitions.js"></script>
</body>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Ambil Referensi Section
    const loginSection = document.getElementById("login-section");
    const registerSection = document.getElementById("register-section");
    const forgotSection = document.getElementById("forgot-password-section");
    const verifySection = document.getElementById("verify-code-section");
    const resetSection = document.getElementById("reset-password-section");

    // Tombol Navigasi Manual
    const btnShowForgot = document.querySelector('a[href="<?= BASE_URL ?>/auth/forgot"]');
    const btnBackToLogin = document.getElementById('back-to-login-from-forgot');

    // Fungsi Helper Buka Section
    function openSection(section) {
      // Tutup semua dulu biar aman
      [loginSection, registerSection, forgotSection, verifySection, resetSection].forEach(el => {
        if (el) {
          el.classList.add("hidden");
          el.classList.add("opacity-0", "scale-95");
        }
      });

      // Buka yang diminta
      if (section) {
        section.classList.remove("hidden");
        setTimeout(() => {
          section.classList.remove("opacity-0", "scale-95");
        }, 20);
      }
    }

    // --- LOGIKA SESSION DARI PHP ---
    <?php if (isset($_SESSION['open_modal'])): ?>
      <?php $modalType = $_SESSION['open_modal']; ?>

      <?php if ($modalType == 'login'): ?>
        openSection(loginSection);
      <?php elseif ($modalType == 'register'): ?>
        openSection(registerSection);
      <?php elseif ($modalType == 'forgot_password'): ?>
        openSection(forgotSection);
      <?php elseif ($modalType == 'verify_code'): ?>
        openSection(verifySection);
      <?php elseif ($modalType == 'reset_password'): ?>
        openSection(resetSection);
      <?php endif; ?>

      <?php unset($_SESSION['open_modal']); ?>
    <?php endif; ?>

    // --- EVENT LISTENER TOMBOL ---

    // Klik "Forgot Password?" di form Login
    if (btnShowForgot) {
      btnShowForgot.addEventListener('click', (e) => {
        e.preventDefault();
        openSection(forgotSection);
      });
    }

    // Klik "Kembali ke Login" di form Forgot
    if (btnBackToLogin) {
      btnBackToLogin.addEventListener('click', (e) => {
        e.preventDefault();
        openSection(loginSection);
      });
    }

    // Script Pindah Fokus OTP (Auto Next Input)
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      });
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
          otpInputs[index - 1].focus();
        }
      });
    });

  });
</script>

</html>