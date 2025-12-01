document.addEventListener("DOMContentLoaded", () => {
  const btnDosen = document.getElementById("btn-dosen");
  const btnMahasiswa = document.getElementById("btn-mahasiswa");
  const btnAlumni = document.getElementById("btn-alumni");
  const allButtons = [btnDosen, btnMahasiswa, btnAlumni];

  const nimNipInput = document.getElementById("nim-nip-input");
  const roleNameInput = document.getElementById("role_name");
  const emailInput = document.querySelector('input[name="email"]');

  // Ambil elemen container yang ingin disembunyikan/ditampilkan
  const studyProgramField = document.getElementById("study-program-field");
  const admissionDateField = document.getElementById("admission-date-field");

  const activeClasses = ["bg-[#5e5e8f]", "text-white", "shadow-md"];
  const inactiveClasses = ["text-gray-600", "hover:bg-gray-200"];

  function switchRole(role) {
    // 1. Atur Tampilan Tombol
    allButtons.forEach((button) => {
      button.classList.remove(...activeClasses);
      button.classList.add(...inactiveClasses);
    });

    let selectedButton;
    if (role === "dosen") selectedButton = btnDosen;
    if (role === "mahasiswa") selectedButton = btnMahasiswa;
    if (role === "alumni") selectedButton = btnAlumni;

    if (selectedButton) {
      selectedButton.classList.add(...activeClasses);
      selectedButton.classList.remove(...inactiveClasses);
    }

    // 2. Atur Placeholder Input NIM/NIP
    let placeholderText = "NIM";
    if (role === "dosen") {
      placeholderText = "NIP";
    } else if (role === "alumni") {
      placeholderText = "NIM/NIP";
    }

    if (nimNipInput) {
      nimNipInput.placeholder = placeholderText;
    }

    if (emailInput) {
      if (role === "mahasiswa") {
        emailInput.placeholder = "nama@stu.pnj.ac.id";
      } else if (role === "dosen") {
        emailInput.placeholder = "nama@tik.pnj.ac.id";
      } else if (role === "alumni") {
        emailInput.placeholder = "nama@gmail.com";
      }
    }

    // 3. Update Hidden Input (Penting untuk dikirim ke PHP)
    if (roleNameInput) {
      roleNameInput.value = role;
    }

    // --- [BAGIAN BARU] LOGIKA SEMBUNYIKAN FIELD ---
    if (role === "dosen") {
      // Jika Dosen: Sembunyikan Prodi & Tahun Masuk
      if (studyProgramField) studyProgramField.classList.add("hidden");
      if (admissionDateField) admissionDateField.classList.add("hidden");

      // Optional: Reset value agar tidak terkirim data sampah
      const prodiSelect = studyProgramField.querySelector("select");
      if (prodiSelect) prodiSelect.value = "";
    } else {
      // Jika Mahasiswa/Alumni: Tampilkan
      if (studyProgramField) studyProgramField.classList.remove("hidden");
      if (admissionDateField) admissionDateField.classList.remove("hidden");
    }
  }

  // Event Listeners
  if (btnDosen) btnDosen.addEventListener("click", () => switchRole("dosen"));
  if (btnMahasiswa)
    btnMahasiswa.addEventListener("click", () => switchRole("mahasiswa"));
  if (btnAlumni)
    btnAlumni.addEventListener("click", () => switchRole("alumni"));

  // Set default role saat load
  switchRole("mahasiswa");
});
