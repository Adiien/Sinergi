<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SINERGI</title>
    <link href="<?= BASE_URL ?>/assets/css/output.css" rel="stylesheet" />
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
  <nav id="main-nav" class="bg-(--blue-gray) p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
      <a href="#" class="flex items-center space-x-3">
        <div class="bg-white rounded-full p-1.5">
          <span class="text-[#36364c] text-xs font-bold">LOGO</span>
        </div>
          <span class="text-white text-xl tracking-widest font-azeret">SINERGI</span>
      </a>
      <div class="hidden md:flex items-center space-x-8 mr-8">
        <div class="space-x-8">
          <a href="#" class="text-gray-300 hover:text-white transition duration-300 font-helvetica">Home</a>
          <a href="#" class="text-gray-300 hover:text-white transition duration-300">About</a>
        </div>
        <div class="flex items-center space-x-4">
          <a id="login-button-nav" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
            Login
          </a>
          <a id="register-button-nav"  class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
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
        <div class="flex justify-center md:justify-start space-x-4">
          <a href="#" class="bg-indigo-600 text-white font-semibold py-3 px-8 rounded-full hover:bg-indigo-700 transition duration-300">
            Mulai Sekarang
          </a>
        </div>
      </div>
      <div class="md:w-7/12 flex justify-center md:justify-end h-full">
      <img 
        src="<?= BASE_URL ?>/assets/vector.png" 
        alt="Hero Image" 
        class="rounded-lg w-auto h-auto"
      >
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
        <form action="/Sinergi/config/simpan_user.php" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 w-11/12 mx-auto">
                <div class="relative">
                    <input type="text" id="nim-nip-input" name="nim" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIM" required>
                </div>
                <div id="study-program-field" class="relative transition-all duration-300">
                    <select id="program_studi" name="program_studi" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" required>
                        <option value="" disabled selected>Pilih program studi</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Teknik Multimedia dan Jaringan">Teknik Multimedia dan Jaringan</option>
                        <option value="Teknik Multimedia dan Desain">Teknik Multimedia dan Desain</option>
                        <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan</option>
                    </select>
                </div>
                <div class="relative">
                    <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Full name" required>
                </div>
                <div class="relative">
                    <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Email" required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                      <a href="#" class="text-sm text-indigo-600 hover:underline">use phone instead</a>
                    </div>
                </div>
                <div id="admission-date-field" class="relative transition-all duration-300">
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Date of Admission</label>
                    <div class="flex space-x-2">
                        <select name="admission_day" class="w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option>06</option>
                        </select>
                        <select name="admission_month" class="w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option>Oct</option>
                        </select>
                        <select name="admission_year" class="w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option>2025</option>
                        </select>
                    </div>
                </div>

                <div class="relative">
                    <label for="password_reg" class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
                    <div class="relative">
                        <input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Password" required>
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

  <section id="login-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50">
    <h2 class="text-3xl font-bold text-center text-[#ffffff] mb-8">Login</h2>
    <div class="bg-white p-4 rounded-xl shadow-2xl w-full max-w-2xl">
        <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center border border-gray-300 mb-2">
                <span class="font-semibold text-gray-700">LOGO</span>
            </div>
            <h2 class="text-3xl font-bold tracking-[0.2em] text-gray-800">SINERGI</h2>
        </div>
        <p class="text-center text-gray-600 mb-6 -mt-2">
            Selamat datang kembali! Silakan login untuk melanjutkan.
        </p>

        <?php if (!empty($_SESSION['register_success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 w-3/4 mx-auto">
                <?= htmlspecialchars($_SESSION['register_success']) ?>
            </div>
            <?php unset($_SESSION['register_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['login_error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-3/4 mx-auto">
                <?= htmlspecialchars($_SESSION['login_error']) ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>/auth/login" method="POST">
            <div class="grid grid-cols-1 gap-y-5">
                <div class="relative w-3/4 mx-auto">
                    <input type="text" id="identifier" name="identifier" class="w-full pl-4 pr-28 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Email / NIM / NIP" required>
                </div>
                
                <div class="relative w-3/4 mx-auto">
                    <input type="password" id="password_log" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Password" required>
                </div>

                <div class="relative w-3/4 mx-auto">
                    <div class="flex items-center space-x-3 mb-2">
                        <img id="captcha_image" src="<?= BASE_URL ?>/captcha.php" alt="CAPTCHA" class="border rounded-lg cursor-pointer" title="Klik untuk muat ulang">
                        <button type="button" id="reload_captcha" class="text-sm text-indigo-600 hover:underline">
                            Muat Ulang
                        </button>
                    </div>
                    <input type="text" id="captcha_input" name="captcha_input" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan teks di atas" required autocomplete="off" maxlength="5">
                </div>
                
                <div class="w-3/4 mx-auto text-right">
                    <a href="#" class="text-xs font-semibold text-indigo-600 hover:underline">Forgot Password</a>
                </div>
            </div>
            <button type="submit" class="w-3/4 block mx-auto bg-[#5e5e8f] text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-800 transition duration-300 mt-8">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Don't have an account?
            <a href="#" class="text-indigo-600 hover:underline font-medium" id="show-register-from-login">Register</a>
        </p>
    </div>
</section>

<script>
    function reloadCaptcha() {
        var captchaImg = document.getElementById('captcha_image');
        if (captchaImg) {
            captchaImg.src = '<?= BASE_URL ?>/captcha.php?t=' + new Date().getTime();
        }
    }
    var reloadBtn = document.getElementById('reload_captcha');
    var captchaImg = document.getElementById('captcha_image');
    if (reloadBtn) reloadBtn.addEventListener('click', reloadCaptcha);
    if (captchaImg) captchaImg.addEventListener('click', reloadCaptcha);
</script>
    </div>
</section>
  <script src="<?= BASE_URL ?>/assets/js/register.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/transitions.js"></script>
  </body>
</html>
