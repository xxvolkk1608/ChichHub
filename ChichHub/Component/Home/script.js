// script.js
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    const blurBackground = document.querySelector('.blur-background');
  
    hamburger.addEventListener('click', () => {
      navLinks.classList.toggle('active');
      blurBackground.classList.toggle('active'); // เบลอพื้นหลังเมื่อเมนูเปิด
    });
  
    // ปิดเมนูเมื่อคลิกที่เบลอพื้นหลัง
    blurBackground.addEventListener('click', () => {
      navLinks.classList.remove('active');
      blurBackground.classList.remove('active');
    });
});
