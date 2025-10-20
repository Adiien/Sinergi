<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SINERGI</title>
    <link href="./assets/css/output.css" rel="stylesheet" />
    <style>
      /* Anda dapat menambahkan font kustom seperti Poppins di sini jika diperlukan */
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap");
      body {
        font-family: "Poppins", sans-serif;
      }
    </style>
  </head>
  <body>
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
  </body>
</html>
