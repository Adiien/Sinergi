document.addEventListener("DOMContentLoaded", () => {
  const heroSection = document.getElementById("hero-section");
  const registerSection = document.getElementById("register-section");
  const loginSection = document.getElementById("login-section");
  const navSection = document.getElementById("main-nav");

  const section = [loginSection, registerSection];

  if (!heroSection || !registerSection || !loginSection || !navSection) {
    console.error("One or more sections are missing!");
    return;
  }

  const showHeroButton = document.getElementById("home-button");
  const showLogoButton = document.getElementById("logo-button");
  const showRegisterButton = document.getElementById("register-button-nav");
  const showRegisterFromLogin = document.getElementById(
    "show-register-from-login"
  );
  const showLoginButton = document.getElementById("login-button-nav");
  const showLoginFromRegister = document.getElementById(
    "show-login-from-register"
  );
  function blurBackground() {
    navSection.classList.add("blur-sm", "pointer-events-none");
    heroSection.classList.add("blur-sm", "pointer-events-none");
  }

  function unblurBackground() {
    navSection.classList.remove("blur-sm", "pointer-events-none");
    heroSection.classList.remove("blur-sm", "pointer-events-none");
  }

  function showSection(SectionToShow) {
    blurBackground();

    section.forEach((sections) => {
      if (sections === SectionToShow) {
        sections.classList.remove("hidden");
        setTimeout(() => {
          sections.classList.remove("opacity-0", "scale-95");
        }, 20);
      } else {
        sections.classList.add("hidden", "opacity-0", "scale-95");
      }
    });
  }

  function showHero() {
    unblurBackground();

    section.forEach((sections) => {
      sections.classList.add("opacity-0", "scale-95");
      setTimeout(() => {
        sections.classList.add("hidden");
      }, 300);
    });
  }
  if (showLoginButton) {
    showLoginButton.addEventListener("click", (e) => {
      e.preventDefault();
      showSection(loginSection);
    });
  }
  if (showLoginFromRegister) {
    showLoginFromRegister.addEventListener("click", (e) => {
      e.preventDefault();
      showSection(loginSection);
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
      showHero();
    });
  }
  if (showLogoButton) {
    showLogoButton.addEventListener("click", (e) => {
      e.preventDefault();
      showHero();
    });
  }
  window.addEventListener("keydown", (event) => {
    if (event.key === "Escape" || event.key === "Esc") {
      showHero();
    }
  });
});
