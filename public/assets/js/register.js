document.addEventListener("DOMContentLoaded", () => {
  const btnDosen = document.getElementById("btn-dosen");
  const btnMahasiswa = document.querySelectorAll("btn-mahasiswa");
  const btnAlumni = document.getElementById("btn-alumni");
  const allButtons = [btnDosen, ...btnMahasiswa, btnAlumni];

  const nimNipLabel = document.getElementById("nim-nip-label");
  const nimNipInput = document.getElementById("nim-nip-input");
});
