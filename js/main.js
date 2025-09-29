// Basic auto-scroll testimonial carousel
const carousel = document.querySelector('#carousel > div');
let index = 0;

setInterval(() => {
  index = (index + 1) % 3;
  carousel.style.transform = `translateX(-${index * 100}%)`;
}, 4000);

// Intersection fade-in
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('opacity-100', 'translate-y-0');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('[data-fade]').forEach(el => {
  el.classList.add('opacity-0', 'translate-y-4', 'transition', 'duration-700');
  observer.observe(el);
});
