const modal = document.getElementById("modal");
const open = document.getElementById("open");
const close = document.getElementById("close");

open.addEventListener("click", function(){
  modal.style.display = "block";
});

close.addEventListener("click", function(){
  modal.style.display = "none";
});