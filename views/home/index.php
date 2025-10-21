<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SINERGI</title>
    <link href="<?= BASE_URL ?>/assets/css/output.css" rel="stylesheet" />
  </head>
  <body class="h-screen overflow-hidden">
  <nav class="bg-gray-800 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
      <a href="#" class="flex items-center space-x-3">
        <div class="bg-white rounded-full p-1.5">
          <span class="text-[#36344B] text-xs font-bold">LOGO</span>
        </div>
          <span class="text-white text-xl font-semibold tracking-widest">SINERGI</span>
      </a>

 
    <div class="hidden md:flex items-center space-x-8">
      <div class="space-x-8">
        <a href="#" class="text-gray-300 hover:text-white transition duration-300">Home</a>
        <a href="#" class="text-gray-300 hover:text-white transition duration-300">About</a>
      </div>
      <div class="flex items-center space-x-4">
        <a href="#" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
          Login
        </a>
        <a href="#" class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-lg hover:bg-gray-200 transition duration-300">
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

  <section class="bg-[#ffffff] h-screen flex items-center justify-center">
  <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6">
    <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0">
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
        <a href="#" class="text-white border border-white py-3 px-8 rounded-full hover:bg-white hover:text-[#36344B] transition duration-300">
          Pelajari Lebih Lanjut
        </a>
      </div>
    </div>

    <div class="md:w-1/2 flex justify-center md:justify-end">
      <img 
        src="<?= BASE_URL ?>/assets/vector.png" 
        alt="Hero Image" 
        class="rounded-lg max-w-full h-full"
      >
    </div>
  </div>
</section>
  </body>
</html>
