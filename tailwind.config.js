/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.php", // Ini akan memindai semua file .php di dalam folder views
    "./src/**/*.php", // Anda bisa tambahkan folder lain jika perlu
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
