<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SINERGI</title>
    <link href="<?= BASE_URL ?>/assets/css/output.css" rel="stylesheet" />
    <style>
      .section-fade {
        transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
      }
    </style>
  </head>
  <body class="h-screen overflow-hidden">

  <nav class="bg-(--blue-gray) p-4 shadow-lg">
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
  <script src="<?= BASE_URL ?>/assets/js/transitions.js"></script>
  </body>
</html>
