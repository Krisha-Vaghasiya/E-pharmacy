// Smooth scroll to the categories section
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}


// Add to Cart Button Alert
const cartButtons = document.querySelectorAll('.add-to-cart');

cartButtons.forEach(button => {
    button.addEventListener('click', () => {
        alert('Item added to cart!');
    });
});


// Slide Carousel Function
let currentIndex = 0;

function slideCarousel(direction) {
    const carousel = document.querySelector('.carousel');
    const cardWidth = document.querySelector('.medicine-card').offsetWidth + 20; // Card width + margin
    const maxIndex = carousel.children.length - 1;

    currentIndex += direction;

    if (currentIndex < 0) {
        currentIndex = maxIndex;
    } else if (currentIndex > maxIndex) {
        currentIndex = 0;
    }

    carousel.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
}


