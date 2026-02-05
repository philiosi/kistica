/**
 * KISTI CA - Menu JavaScript
 * 모바일 메뉴 토글 및 네비게이션 기능
 */

document.addEventListener('DOMContentLoaded', function() {
  // 모바일 메뉴 토글
  const menuToggle = document.getElementById('mobile-menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const menuOverlay = document.getElementById('mobile-menu-overlay');
  const menuClose = document.getElementById('mobile-menu-close');

  function openMenu() {
    if (mobileMenu && menuOverlay) {
      mobileMenu.classList.add('active');
      menuOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeMenu() {
    if (mobileMenu && menuOverlay) {
      mobileMenu.classList.remove('active');
      menuOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', openMenu);
  }

  if (menuOverlay) {
    menuOverlay.addEventListener('click', closeMenu);
  }

  if (menuClose) {
    menuClose.addEventListener('click', closeMenu);
  }

  // ESC 키로 메뉴 닫기
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeMenu();
    }
  });

  // 현재 페이지 네비게이션 항목 활성화
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-item, .bottom-tab');

  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href && currentPath.endsWith(href) ||
        (href && href !== '/' && currentPath.includes(href))) {
      link.classList.add('active');
    }
  });

  // 검색 기능 (선택적)
  const searchInput = document.querySelector('.search-bar input');
  if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query) {
          // 검색 폼 제출 또는 필터링 로직
          const form = this.closest('form');
          if (form) {
            form.submit();
          }
        }
      }
    });
  }

  // 필터 칩 토글
  const filterChips = document.querySelectorAll('.filter-chip');
  filterChips.forEach(chip => {
    chip.addEventListener('click', function() {
      // 단일 선택 모드
      filterChips.forEach(c => c.classList.remove('active'));
      this.classList.add('active');

      // 필터 값 처리 (data-filter 속성 사용)
      const filterValue = this.dataset.filter;
      if (filterValue) {
        filterItems(filterValue);
      }
    });
  });

  // 아이템 필터링 함수
  function filterItems(filter) {
    const items = document.querySelectorAll('[data-status]');
    items.forEach(item => {
      if (filter === 'all' || item.dataset.status === filter) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  }

  // 스크롤 시 헤더 그림자
  const header = document.querySelector('.mobile-header');
  if (header) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 10) {
        header.classList.add('shadow-md');
      } else {
        header.classList.remove('shadow-md');
      }
    });
  }

  // 카드 클릭 시 상세보기 (선택적)
  const clickableCards = document.querySelectorAll('.list-item[data-href]');
  clickableCards.forEach(card => {
    card.style.cursor = 'pointer';
    card.addEventListener('click', function() {
      const href = this.dataset.href;
      if (href) {
        window.location.href = href;
      }
    });
  });
});

// 유틸리티 함수들
const KistiCA = {
  // 토스트 알림 표시
  showToast: function(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-24 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-white text-sm font-medium z-50 transition-opacity duration-300`;

    const colors = {
      info: 'bg-primary',
      success: 'bg-green-500',
      error: 'bg-red-500',
      warning: 'bg-amber-500'
    };

    toast.classList.add(colors[type] || colors.info);
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  },

  // 확인 대화상자
  confirm: function(message) {
    return window.confirm(message);
  },

  // 폼 유효성 검사
  validateForm: function(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const required = form.querySelectorAll('[required]');
    let valid = true;

    required.forEach(field => {
      if (!field.value.trim()) {
        field.classList.add('border-red-500');
        valid = false;
      } else {
        field.classList.remove('border-red-500');
      }
    });

    return valid;
  },

  // 로딩 표시
  showLoading: function(show = true) {
    let loader = document.getElementById('global-loader');

    if (show) {
      if (!loader) {
        loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.className = 'fixed inset-0 bg-black/30 flex items-center justify-center z-50';
        loader.innerHTML = `
          <div class="bg-white rounded-xl p-4 shadow-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          </div>
        `;
        document.body.appendChild(loader);
      }
      loader.style.display = 'flex';
    } else if (loader) {
      loader.style.display = 'none';
    }
  }
};
