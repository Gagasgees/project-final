// HEADER
const header = document.querySelector('header');
function fixedNavbar() {
  header.classList.toggle('scroll', window.pageYOffset > 0)
}

fixedNavbar();
window.addEventListener('scroll', fixedNavbar);

let menu = document.querySelector('#menu-btn');
let userBtn = document.querySelector('#user-btn');

menu.addEventListener('click', function() {
  let nav = document.querySelector('.navbar');
  nav.classList.toggle('active');
})

userBtn.addEventListener('click', function() {
  let userBox = document.querySelector('.user-box');
  userBox.classList.toggle('active')
})



// HOME PAGE SLIDER

"use strict"

const leftArrow = document.querySelector('.left-arrow .fa-chevron-left'),
      rightArrow = document.querySelector('.right-arrow .a-chevron-righ'),
      slider = document.querySelector('.slider');

/*---------- scroll to right -----------*/
if (slider) {
  function scrollRight() {
    if (slider.scrollWidth - slider.clientWidth === slider.scrollLeft) {
      slider.scrollTo({
        left: 0,
        behavior: "smooth"
      });
    }else {
      slider.scrollBy({
        left: window.innerWidth,
        behavior: "smooth"
      })
    }
  }

  /*--------- scroll to left -------------*/
  function scrollLeft() {
    slider.scrollBy({
      left: -window.innerWidth,
      behavior: "smooth"
    })
  }
  let timerId = setInterval(scrollRight, 7000);

  /*---------- reset timer to scroll right ---------*/
  function resetTimer() {
    clearInterval(timerId);
    timerId = setInterval(scrollRight, 7000);
  }

  rightArrow?.addEventListener('click', scrollRight);
  leftArrow?.addEventListener('click', scrollLeft);
  slider.addEventListener('click', resetTimer);

  /*---------- scroll event ------------*/
  slider.addEventListener('click', function(ev) {
    if(ev.target === leftArrow) {
      scrollLeft();
      resetTimer();
    }
  })

  slider.addEventListener('click', function(ev) {
    if(ev.target === rightArrow) {
      scrollRight();
      resetTimer();
    }
  })
}


/*--------- SHOP CATEGORY JS -----------*/
function setupSlider(containerId) {
  const container = document.getElementById(containerId);

  if (!container) {
    console.warn(`Elemen dengan ID '${containerId}' tidak ditemukan.`);
    return;
  }
  const slides = container.querySelectorAll(".slide");
  const dotsContainer = container.querySelector(".dots");

  let currentSlide = 0;
  dotsContainer.innerHTML = "";

  slides.forEach((_, i) => {
    const dot = document.createElement("span");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => showSlide(i));
    dotsContainer.appendChild(dot);
  });

  const dots = dotsContainer.querySelectorAll(".dot");

  function showSlide(index) {
    slides[currentSlide].classList.remove("active");
    dots[currentSlide].classList.remove("active");

    slides[index].classList.add("active");
    dots[index].classList.add("active");

    currentSlide = index;
  }

  //*---------- touch and drag ------------*/
  let startX = 0;
  let endX = 0;

  // Touch Events
  container.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  });

  container.addEventListener("touchend", (e) => {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
  });

  // Mouse Events
  container.addEventListener("mousedown", (e) => {
    startX = e.clientX;
  });

  container.addEventListener("mouseup", (e) => {
    endX = e.clientX;
    handleSwipe();
  });

  function handleSwipe() {
    const threshold = 50;
    if (endX - startX > threshold && currentSlide > 0) {
      showSlide(currentSlide - 1);
    } else if (startX - endX > threshold && currentSlide < slides.length - 1) {
      showSlide(currentSlide + 1);
    }
  }

  showSlide(0);
}

document.addEventListener("DOMContentLoaded", function () {
  setupSlider("slider-big-offers");
  setupSlider("slider-new-taste");

  /* --------------- Testimonial slides ------------- */
  const slides = document.querySelectorAll(".testimonial-item");

  if (!slides.length) {
    console.warn("Tidak ada testimonial-item ditemukan");
    return;
  }

  let index = 0;

  function showTestimonial(i) {
    slides.forEach((s) => s.classList.remove("active"));
    slides[i].classList.add("active");
  }

  document.getElementById("nextSlide")?.addEventListener("click", () => {
    index = (index + 1) % slides.length;
    showTestimonial(index);
  });

  document.getElementById("prevSlide")?.addEventListener("click", () => {
    index = (index - 1 + slides.length) % slides.length;
    showTestimonial(index);
  });

  showTestimonial(index);
});
