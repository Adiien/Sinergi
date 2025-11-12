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
    <?php if (isset($_SESSION['error_message'])): ?>
        <div id="alert-box" class="fixed top-20 right-5 bg-red-500 text-white p-4 rounded-lg shadow-lg z-[100]">
            <?php echo $_SESSION['error_message']; ?>
            <?php unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div id="alert-box" class="fixed top-20 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg z-[100]">
            <?php echo $_SESSION['success_message']; ?>
            <?php unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan ?>
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
        src="<?= BASE_URL ?>/public/assets/vector.png" 
        alt="Hero Image" 
        class="rounded-lg w-auto h-auto"
      >
      </div>
    </div>
  </section>

      setTimeout(function() {
        const alertBox = document.getElementById('alert-box');
        if (alertBox) {
          alertBox.style.display = 'none';
        }
      }, 3000);
    </script>
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
  <!-- Login Section-->
  <section id="login-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50">
    <h2 class="text-3xl font-bold text-center text-[#ffffff] mb-8">Login</h2>
    <div class="bg-white p-4 rounded-xl shadow-2xl w-full max-w-2xl">
        <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center border border-gray-300 mb-2">
                <span class="font-semibold text-gray-700">LOGO</span>
            </div>
            <h2 class="text-3xl font-bold tracking-[0.2em] text-gray-800">SINERGI</h2>
        </div>
        <form action="<?= BASE_URL ?>/auth/login" method="POST">
            <div class="grid grid-cols-1 gap-y-5">
                <div class="relative w-3/4 mx-auto">
                    <input type="text" id="identifier" name="identifier" class="w-full pl-4 pr-28 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Email / NIM / NIP" required>
                </div>
                <div class="relative w-3/4 mx-auto">
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Password" required>
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
  <script src="<?= BASE_URL ?>/public/assets/js/register.js"></script>
  <script src="<?= BASE_URL ?>/public/assets/js/transitions.js"></script>
  </body>
</html>
