document.addEventListener("DOMContentLoaded", function () {
  const preloader = document.getElementById("js-preloader");
  if (preloader) {
    preloader.classList.add("loaded");
    setTimeout(() => {
      preloader.style.display = "none";
    }, 500); 
  }
});
