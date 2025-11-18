document.addEventListener("DOMContentLoaded", () => {
  const btnDosen = document.getElementById("btn-dosen");
  const btnMahasiswa = document.getElementById("btn-mahasiswa");
  const btnAlumni = document.getElementById("btn-alumni");
  const allButtons = [btnDosen, btnMahasiswa, btnAlumni];

  const nimNipInput = document.getElementById("nim-nip-input");
  const roleNameInput = document.getElementById("role_name"); // Ambil hidden input

  const activeClasses = ["bg-[#5e5e8f]", "text-white", "shadow-md"];
  const inactiveClasses = ["text-gray-600", "hover:bg-gray-200"];

  function switchRole(role) {
    allButtons.forEach((button) => {
      button.classList.remove(...activeClasses);
      button.classList.add(...inactiveClasses);
    });

    let selectedButton;
    if (role === "dosen") selectedButton = btnDosen;
    if (role === "mahasiswa") selectedButton = btnMahasiswa;
    if (role === "alumni") selectedButton = btnAlumni;

    selectedButton.classList.add(...activeClasses);
    selectedButton.classList.remove(...inactiveClasses);

    let placeholderText = "NIM";
    let inputName = "nim-nip-input"; // Biarkan name tetap sama untuk simplicity, kita handle di controller

    if (role === "dosen") {
      placeholderText = "NIP";
    } else if (role === "alumni") {
      placeholderText = "NIM/NIP";
    }

    if (nimNipInput) {
      nimNipInput.placeholder = placeholderText;
      // nimNipInput.name = inputName; // Kita tidak perlu ganti nama input
    }

    // UPDATE NILAI HIDDEN INPUT
    if (roleNameInput) {
      roleNameInput.value = role;
    }
  }
  btnDosen.addEventListener("click", () => switchRole("dosen"));
  btnMahasiswa.addEventListener("click", () => switchRole("mahasiswa"));
  btnAlumni.addEventListener("click", () => switchRole("alumni"));

  // Set default role saat load
  switchRole("mahasiswa");
});
