// js/cursos_user.js

document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".card button");

  botones.forEach(btn => {
    btn.addEventListener("click", () => {
      btn.innerText = "Inscrito ✅";
      btn.style.backgroundColor = "#22c55e"; // verde
      btn.disabled = true;
    });
  });
});
