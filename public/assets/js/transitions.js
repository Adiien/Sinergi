document.addEventListener("DOMContentLoaded", () => {
  const heroSection = document.getElementById("hero-section");
  const registerSection = document.getElementById("register-section");

  if (!heroSection || !registerSection) {
    console.error("One or more sections are missing!");
    return;
  }

  const sections = [heroSection, registerSection];

  const showHeroButton = document.getElementById("home-button");
  const showLogoButton = document.getElementById("logo-button");
  const showRegisterButton = document.getElementById("register-button-nav");

  const showRegisterFromLogin = document.getElementById(
    "show-register-from-login"
  );

  function showSection(sectionToShow) {
    sections.forEach((section) => {
      if (section === sectionToShow) {
        section.classList.remove("hidden");
        setTimeout(() => {
          section.classList.remove("opacity-0", "scale-95");
        }, 20);
      } else {
        section.classList.add("opacity-0", "scale-95");
        setTimeout(() => {
          section.classList.add("hidden");
        }, 500);
      }
    });
  }

  if (showRegisterButton) {
    showRegisterButton.addEventListener("click", (e) => {
      e.preventDefault();
      showSection(registerSection);
    });
  }

  if (showRegisterFromLogin) {
    showRegisterFromLogin.addEventListener("click", (e) => {
      e.preventDefault();
      showSection(registerSection);
    });
  }

  if (showHeroButton) {
    showHeroButton.addEventListener("click", (e) => {
      e.preventDefault();
      showSection(heroSection);
    });
  }

  if (showLogoButton) {
    showLogoButton.addEventListener("click", (e) => {
      e.preventDefault();
      showSection(heroSection);
    });
  }
});
