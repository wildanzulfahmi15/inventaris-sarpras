window.addEventListener('DOMContentLoaded', () => {

  const bannerContent = document.getElementById('banner-content');
  if (bannerContent) {
    window.addEventListener('load', () => {
      bannerContent.classList.add('show');
    });
  }

  const lihatBtn = document.getElementById('lihat-btn');
  const about = document.getElementById('about');

  if (lihatBtn && about) {
    lihatBtn.addEventListener('click', () => {
      about.scrollIntoView({ behavior: 'smooth' });
    });
  }

  const cards = document.querySelectorAll('.card');
  if (cards.length) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });

    cards.forEach(card => observer.observe(card));
  }

  const year = document.getElementById('year');
  if (year) {
    year.textContent = new Date().getFullYear();
  }

const banner = document.querySelector('.banner');

if (banner) {
  const images = [
    banner.dataset.img1,
    banner.dataset.img2,
    banner.dataset.img3
  ].filter(Boolean);

  const layer1 = banner.querySelector('.layer1');
  const layer2 = banner.querySelector('.layer2');

  let index = 0;
  let activeLayer = layer1;
  let nextLayer = layer2;

  activeLayer.style.backgroundImage = `url('${images[index]}')`;

  setInterval(() => {
    index = (index + 1) % images.length;

    nextLayer.style.backgroundImage = `url('${images[index]}')`;
    nextLayer.style.opacity = 1;
    activeLayer.style.opacity = 0;

    // swap layer
    [activeLayer, nextLayer] = [nextLayer, activeLayer];

  }, 5000); // 5 detik (bukan 500000 😅)
}

});