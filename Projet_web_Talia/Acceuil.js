const modal = document.getElementById("modal");
const open = document.getElementById("open");
const close = document.getElementById("close");

open.addEventListener("click", function() {
  modal.style.display = "flex";
  document.body.style.overflow = "hidden";
});

close.addEventListener("click", function() {
  modal.style.display = "none";
  document.body.style.overflow = "auto";
});


function SousMenu(element) {
    const menu = element.nextElementSibling;
    menu.classList.toggle('ouvert');
}

