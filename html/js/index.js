const slides = document.querySelector(".slides");
const dots = [...document.querySelectorAll(".dot")];
const slideWidth = slides.clientWidth / 4;
let currentSlide = 0;

function showSlide(n) {
    slides.style.transform = `translateX(-${n * slideWidth}px)`;
        dots[currentSlide].classList.remove("active");
        dots[n].classList.add("active");
        currentSlide = n;
}

dots.forEach((dot, i) => {
    dot.addEventListener("click", () => {
        showSlide(i);
    });
});

setInterval(() => {
    if (currentSlide === 3) {
        showSlide(0);
    } else {
        showSlide(currentSlide + 1);
    }
}, 5000);