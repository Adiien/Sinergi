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
        <a href="#" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
          Login
        </a>
        <a id="register-button-nav" href="<?= BASE_URL ?>/register/index" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
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

<section id="register-section" class="h-screen flex flex-col items-center justify-center pt-2 section-fade hidden opacity-0 scale-95 fixed inset-0 z-50 bg-[#5e5e8f]/50">
    <h2 class="text-3xl font-bold text-center text-[#ffffff] mb-8">Registrasi</h2>
    <div class="bg-white p-4 rounded-xl shadow-2xl w-full max-w-3xl">
        <div class="flex items-center justify-center space-x-2 mb-2">
        <button type="button" class="text-gray-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
            Dosen
        </button>
        <button type="button" class="bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md">
            Mahasiswa
        </button>
        <button type="button" class="text-gray-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
            Alumni
        </button>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Create an account</h2>
        <form action="#" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <label for="nim" class="block text-gray-700 font-semibold mb-2 text-sm">NIM</label>
                    <input type="text" id="nim" name="nim" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                
                <div>
                    <label for="study_program" class="block text-gray-700 font-semibold mb-2 text-sm">study program</label>
                    <select id="study_program" name="study_program" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" required>
                        <option value="" disabled selected>Pilih program studi</option>
                        <option value="ti">Teknik Informatika</option>
                        <option value="si">Sistem Informasi</option>
                        <option value="dkv">Desain Komunikasi Visual</option>
                    </select>
                </div>
                
                <div>
                    <label for="full_name" class="block text-gray-700 font-semibold mb-2 text-sm">Full name</label>
                    <input type="text" id="full_name" name="full_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="email_reg" class="block text-gray-700 font-semibold text-sm">Email</label>
                        <a href="#" class="text-sm text-indigo-600 hover:underline">use phone instead</a>
                    </div>
                    <input type="email" id="email_reg" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                <div>
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

                <div>
                    <label for="password_reg" class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
                    <div class="relative">
                        <input type="password" id="password_reg" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                </div>
            </div> <button type="submit" class="w-full bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-800 transition duration-300 mt-8">
                Create Account
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Already have an account?
            <a href="#" class="text-indigo-600 hover:underline font-medium" id="show-login-from-register">Login</a>
        </p>
    </div>
</section>
  <script src="<?= BASE_URL ?>/assets/js/transitions.js"></script>
  </body>
</html>
