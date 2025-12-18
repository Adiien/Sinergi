document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("profileBtn");
  const menu = document.getElementById("profileMenu");

  if (!btn || !menu) return;

  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    menu.classList.toggle("hidden");
  });

  document.addEventListener("click", () => {
    menu.classList.add("hidden");
  });
});

