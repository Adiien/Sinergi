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
    <div class="relative w-full h-screen bg-white">
      <img class="absolute top-0 left-0 w-full h-full" src="./assets/vector.png" alt="Logo" />
      <nav
        class="absolute top-0 left-0 w-full h-[14%] bg-[#36364c] shadow-[0px_5px_5px_rgba(0,0,0,0.25)] flex items-center justify-between px-8"
      >
        <div class="flex items-center gap-2">
          <div
            class="relative flex items-center justify-center w-12 h-12 bg-white rounded-full"
          >
            <div
              class="absolute text-center text-sm font-bold text-[#36364c] leading-none"
            >
              LO<br />GO
            </div>
          </div>
          <div class="text-white text-2xl font-bold tracking-wider">
            SINERGI
          </div>
        </div>

        <div class="flex items-center gap-8">
          <a
            href="#"
            class="text-white text-xl font-normal tracking-[1px] leading-tight hover:text-gray-300 transition-colors"
            >Home</a
          >
          <a
            href="#"
            class="text-white text-xl font-normal tracking-[1px] leading-tight hover:text-gray-300 transition-colors"
            >About</a
          >

          <a
            href="#"
            class="bg-white text-black text-xl font-normal tracking-[1px] whitespace-nowrap rounded-[14px] py-3 px-10 hover:bg-gray-200 transition-colors"
            >Login</a
          >

          <a
            href="#"
            class="bg-white text-black text-xl font-normal tracking-[1px] whitespace-nowrap rounded-[14px] py-3 px-10 hover:bg-gray-200 transition-colors"
            >Registrasi</a
          >
        </div>
      </nav>
    </div>
  </body>
</html>
