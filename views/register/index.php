<section id="register-section" class="h-screen flex items-center justify-center pt-16 section-fade hidden opacity-0 scale-95">
  <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-md">
    <h2 class="text-3xl font-bold text-center text-[#36344B] mb-8">Registrasi</h2>
    <form action="#" method="POST">
      <div class="mb-4">
        <label for="nama_lengkap" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      </div>
      <div class="mb-4">
        <label for="username_reg" class="block text-gray-700 font-semibold mb-2">Username</label>
        <input type="text" id="username_reg" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      </div>
      <div class="mb-4">
        <label for="email_reg" class="block text-gray-700 font-semibold mb-2">Email</label>
        <input type="email" id="email_reg" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      </div>
      <div class="mb-6">
        <label for="password_reg" class="block text-gray-700 font-semibold mb-2">Password</label>
        <input type="password" id="password_reg" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      </div>
      <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-300">
        Registrasi
      </button>
    </form>
    <p class="text-center text-gray-600 mt-6">
      Sudah punya akun?
      <a href="#" class="text-indigo-600 hover:underline" id="show-login-from-register">Login</a>
    </p>
  </div>
</section>