document.addEventListener("DOMContentLoaded", () => {
  const btnDosen = document.getElementById("btn-dosen");
  const btnMahasiswa = document.getElementById("btn-mahasiswa");
  const btnAlumni = document.getElementById("btn-alumni");
  const allButtons = [btnDosen, btnMahasiswa, btnAlumni];

  const nimNipInput = document.getElementById("nim-nip-input");

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
    let inputName = "nim";

    if (role === "dosen") {
      placeholderText = "NIP";
      inputName = "nip";
    } else if (role === "alumni") {
      placeholderText = "NIM/NIP";
      inputName = "nim_nip";
    }

    if (nimNipInput) {
      nimNipInput.placeholder = placeholderText;
      nimNipInput.name = inputName;
    }
  }
  btnDosen.addEventListener("click", () => switchRole("dosen"));
  btnMahasiswa.addEventListener("click", () => switchRole("mahasiswa"));
  btnAlumni.addEventListener("click", () => switchRole("alumni"));
});
