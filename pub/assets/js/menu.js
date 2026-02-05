// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
  var menuBtn = document.getElementById('mobile-menu-btn');
  var menuClose = document.getElementById('mobile-menu-close');
  var menuOverlay = document.getElementById('mobile-menu-overlay');
  var mobileMenu = document.getElementById('mobile-menu');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', function() {
      mobileMenu.classList.remove('hidden');
    });
  }

  if (menuClose && mobileMenu) {
    menuClose.addEventListener('click', function() {
      mobileMenu.classList.add('hidden');
    });
  }

  if (menuOverlay && mobileMenu) {
    menuOverlay.addEventListener('click', function() {
      mobileMenu.classList.add('hidden');
    });
  }
});
