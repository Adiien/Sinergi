document.addEventListener("DOMContentLoaded", () => {
  const heroSection = document.getElementById("hero-section");
  const registerSection = document.getElementById("register-section");
  const loginSection = document.getElementById("login-section");
  const navSection = document.getElementById("main-nav");

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

  function showRegister() {
    registerSection.classList.remove("hidden");
    setTimeout(() => {
      registerSection.classList.remove("opacity-0", "scale-95");
    }, 20);

    heroSection.classList.add("blur-sm", "pointer-events-none");
    navSection.classList.add("blur-sm", "pointer-events-none");
  }

  function showLogin() {
    loginSection.classList.remove("hidden");
    setTimeout(() => {
      loginSection.classList.remove("opacity-0", "scale-95");
    }, 20);

    heroSection.classList.add("blur-sm", "pointer-events-none");
    navSection.classList.add("blur-sm", "pointer-events-none");
  }

  if (showRegisterButton) {
    showRegisterButton.addEventListener("click", (e) => {
      e.preventDefault();
      showRegister();
    });
  }

  if (showRegisterFromLogin) {
    showRegisterFromLogin.addEventListener("click", (e) => {
      e.preventDefault();
      showRegister();
    });
  }
  if (showLoginButton) {
    showLoginButton.addEventListener("click", (e) => {
      e.preventDefault();
      showLogin();
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
