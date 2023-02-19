/* header sticky */

const nav = document.querySelector(".nav");

window.addEventListener("scroll", function () {

  window.scrollY >= 100 ? nav.classList.add("active") : nav.classList.remove("active");

});


// function bigImg(x) {
//   x.style.height = "64px";
//   x.style.width = "64px";
// }
// $(".image2").attr("src","image1.jpg");




