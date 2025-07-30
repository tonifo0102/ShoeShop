// Admin Dashboard Animations
$(document).ready(function() {
    // Initialize variables
    var adminInit = false;

    // Check if we're on an admin page
    if (window.location.pathname.includes('/admin')) {
        initAdminAnimations();
    }

    function initAdminAnimations() {
        if (adminInit) return; // Prevent double initialization
        adminInit = true;

        // Add card index for staggered animations
        $('.col.card').each(function(index) {
            $(this).css('--card-index', index + 1);
        });

        // Add form group index for staggered animations
        $('.form .form-group').each(function(index) {
            $(this).css('--form-index', index + 1);
        });

        // Initialize DataTables with custom styling
        if ($.fn.dataTable) {
            $('.table-datatables').each(function() {
                var tableId = $(this).attr('id');
                
                if (!$.fn.DataTable.isDataTable('#' + tableId)) {
                    $(this).DataTable({
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Tìm kiếm...",
                            lengthMenu: "Hiển thị _MENU_ kết quả",
                            info: "Hiển thị _START_ đến _END_ của _TOTAL_ kết quả",
                            infoEmpty: "Hiển thị 0 đến 0 của 0 kết quả",
                            infoFiltered: "(lọc từ _MAX_ kết quả)",
                            paginate: {
                                first: "Đầu",
                                last: "Cuối",
                                next: "Sau",
                                previous: "Trước"
                            }
                        },
                        responsive: true,
                        pageLength: 10,
                        drawCallback: function() {
                            $('.dataTables_paginate > .paginate_button').addClass('btn btn-sm');
                        }
                    });
                }
            });
        }

        // Button ripple effect
        $('.box-dashboard button, .modal-container button').on('mousedown', function(e) {
            var $button = $(this);
            
            // Remove any existing ripples
            $button.find('.ripple').remove();
            
            var offset = $button.offset();
            var x = e.pageX - offset.left;
            var y = e.pageY - offset.top;
            
            var $ripple = $('<span class="ripple"></span>');
            $ripple.css({
                top: y + 'px',
                left: x + 'px'
            });
            
            $button.append($ripple);
            
            setTimeout(function() {
                $ripple.remove();
            }, 700);
        });

        // Card counter animation
        $('.col.card .card-body').each(function() {
            var $this = $(this);
            var countTo = parseInt($this.text().replace(/[^\d.-]/g, ''));
            
            if (!isNaN(countTo) && countTo > 0) {
                $this.prop('Counter', 0).animate({
                    Counter: countTo
                }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function(now) {
                        // Format with commas for thousands
                        var formattedNumber = Math.ceil(now).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        
                        // If the original has currency or other text, maintain it
                        if ($this.text().includes('đ')) {
                            $this.text(formattedNumber + 'đ');
                        } else {
                            $this.text(formattedNumber);
                        }
                    }
                });
            }
        });

        // Image upload preview
        $('input[type="file"]').on('change', function(e) {
            var input = this;
            var $preview = $(input).closest('.form-group').find('.preview-image');
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $preview.html('');
                    $('<img>').attr('src', e.target.result).appendTo($preview);
                    $preview.addClass('has-image');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        });

        // Modal open/close animations
        $('[id^=btn-open-modal]').on('click', function() {
            var modalId = $(this).data('modal');
            $('#' + modalId).fadeIn(300);
            $('#' + modalId + ' .modal-container').addClass('animate-in');
        });
        
        $('.modal-close').on('click', function() {
            var $modal = $(this).closest('.modal');
            $modal.find('.modal-container').addClass('animate-out');
            
            setTimeout(function() {
                $modal.fadeOut(300);
                $modal.find('.modal-container').removeClass('animate-out animate-in');
            }, 300);
        });

        // Status button toggle effect
        $('#btn-locked').on('click', function() {
            var $btn = $(this);
            var currentStatus = $btn.data('status');
            
            // Toggle status animation
            $btn.addClass('status-changing');
            
            setTimeout(function() {
                if (currentStatus === 0) {
                    $btn.data('status', 1).text('Đã khóa').removeClass('status-changing');
                } else {
                    $btn.data('status', 0).text('Đang kích hoạt').removeClass('status-changing');
                }
            }, 500);
        });

        // Add dynamic styles
        addAdminStyles();
    }

    function addAdminStyles() {
        var style = document.createElement('style');
        style.type = 'text/css';
        style.innerHTML = `
            .animate-in {
                animation: scaleIn 0.3s ease-out forwards;
            }
            .animate-out {
                animation: scaleOut 0.3s ease-out forwards;
            }
            @keyframes scaleOut {
                from {
                    transform: scale(1);
                    opacity: 1;
                }
                to {
                    transform: scale(0.8);
                    opacity: 0;
                }
            }
            .ripple {
                position: absolute;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.7s linear;
                pointer-events: none;
                width: 120px;
                height: 120px;
                margin-top: -60px;
                margin-left: -60px;
            }
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
            .status-changing {
                overflow: hidden;
                position: relative;
            }
            .status-changing:after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                animation: status-changing 1s infinite;
            }
            @keyframes status-changing {
                from {
                    transform: translateX(-100%);
                }
                to {
                    transform: translateX(100%);
                }
            }
            .preview-image.has-image {
                border-style: solid;
                border-color: #3b82f6;
                overflow: hidden;
            }   
            .form textarea {
                min-height: 120px;
            }
        `;
        document.head.appendChild(style);
    }
}); 
/**
 * Combined Animations JavaScript
 * 
 * This file consolidates animations from:
 * - animations.js (main site animations)
 * - admin-animations.js
 * - guest-animations.js
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize all animations
  initHeaderAnimations();
  initBannerAnimations();
  
  // Check for specific page elements and initialize relevant animations
  if (document.querySelector('.login-form, .register-form, .forgot-password-form, .page.login')) {
    initAuthFormAnimations();
  }
  
  if (document.querySelector('.admin-dashboard')) {
    initAdminDashboardAnimations();
  }
  
  if (document.querySelector('.guest-dashboard')) {
    initGuestDashboardAnimations();
  }
  
  if (document.querySelector('.footer')) {
    initFooterAnimations();
  }
  
  // Initialize smooth scroll for anchor links
  initSmoothScroll();
  
  // Add animation classes for dynamic elements
  addDynamicAnimationClasses();
});

/**
 * Header Animation Functions
 */
function initHeaderAnimations() {
  // Add scroll event for header animations
  window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    const navbarHeader = document.querySelector('.navbar-header');
    
    // Handle standard header
    if (header) {
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    }
    
    // Handle navbar header (app specific)
    if (navbarHeader) {
      if (window.scrollY > 20) {
        navbarHeader.classList.add('shadow-nav');
      } else {
        navbarHeader.classList.remove('shadow-nav');
      }
    }
  });
  
  // Mobile menu toggle animation
  const menuToggle = document.querySelector('.menu-toggle');
  if (menuToggle) {
    menuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      const mobileMenu = document.querySelector('.mobile-menu');
      if (mobileMenu) {
        mobileMenu.classList.toggle('open');
      }
    });
  }
  
  // Shopping cart hover effect
  const cartIcons = document.querySelectorAll('.fa-shopping-cart');
  cartIcons.forEach(icon => {
    icon.addEventListener('mouseenter', function() {
      this.classList.add('cart-wobble');
    });
    
    icon.addEventListener('mouseleave', function() {
      const self = this;
      setTimeout(function() {
        self.classList.remove('cart-wobble');
      }, 800);
    });
  });
}

/**
 * Banner/Hero Animation Functions
 */
function initBannerAnimations() {
  // Initialize any banner/slider animations that require JavaScript
  const sliders = document.querySelectorAll('.hero-slider, .slide-banner');
  
  // Custom slider functionality would go here if needed
  
  // Animate CTA buttons on hover if needed beyond CSS
  const ctaButtons = document.querySelectorAll('.cta-button');
  ctaButtons.forEach(button => {
    button.addEventListener('mouseover', function() {
      // Additional hover effects if needed
    });
  });
}

/**
 * Authentication Forms Animation Functions
 */
function initAuthFormAnimations() {
  // Form validation animations for standard auth forms
  const formInputs = document.querySelectorAll('.auth-form .form-control');
  formInputs.forEach(input => {
    // Add focus animations if needed beyond CSS
    input.addEventListener('focus', function() {
      const formGroup = this.closest('.form-group');
      formGroup.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
      const formGroup = this.closest('.form-group');
      formGroup.classList.remove('focused');
      
      // Add validation animations
      if (this.checkValidity()) {
        formGroup.classList.add('validation-success');
        formGroup.classList.remove('validation-error');
      } else if (this.value !== '') {
        formGroup.classList.add('validation-error');
        formGroup.classList.remove('validation-success');
      }
    });
  });
  
  // Add floating label effect for login page forms
  const loginInputs = document.querySelectorAll('.page.login .group input');
  loginInputs.forEach(input => {
    const label = input.previousElementSibling;
    
    // Check if input has a value on load
    if (input.value) {
      label.classList.add('active');
    }
    
    // On focus add active class
    input.addEventListener('focus', function() {
      label.classList.add('active');
      this.closest('.group').classList.add('focused');
    });
    
    // On blur check if input has a value and remove active class accordingly
    input.addEventListener('blur', function() {
      if (this.value === '') {
        label.classList.remove('active');
      }
      this.closest('.group').classList.remove('focused');
    });
  });
  
  // Password visibility toggle
  const passwordToggles = document.querySelectorAll('.password-toggle');
  passwordToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const passwordField = this.previousElementSibling;
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      
      // Toggle icon
      this.innerHTML = type === 'password' ? 
        '<i class="fa fa-eye"></i>' : 
        '<i class="fa fa-eye-slash"></i>';
    });
  });
  
  // Form submission animations
  const authForms = document.querySelectorAll('.auth-form, .page.login form');
  authForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      // Add submission animation
      const submitBtn = this.querySelector('.auth-btn, button[type="submit"]');
      if (submitBtn) {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';
        submitBtn.disabled = true;
      }
    });
  });
  
  // Confirmation password validation
  const confirmPassword = document.getElementById('confirm-password');
  if (confirmPassword) {
    confirmPassword.addEventListener('keyup', function() {
      const password = document.getElementById('password');
      if (password && password.value && this.value) {
        if (password.value !== this.value) {
          this.style.borderColor = '#ef4444';
        } else {
          this.style.borderColor = '#10b981';
        }
      }
    });
  }
  
  // Phone number formatting
  const phoneInput = document.getElementById('phone');
  if (phoneInput) {
    phoneInput.addEventListener('input', function() {
      let phoneNumber = this.value.replace(/\D/g, '');
      
      // Format as XXX-XXX-XXXX or your preferred format
      if (phoneNumber.length > 0) {
        if (phoneNumber.length <= 4) {
          phoneNumber = phoneNumber;
        } else if (phoneNumber.length <= 7) {
          phoneNumber = phoneNumber.slice(0, 4) + '-' + phoneNumber.slice(4);
        } else {
          phoneNumber = phoneNumber.slice(0, 4) + '-' + phoneNumber.slice(4, 7) + '-' + phoneNumber.slice(7, 11);
        }
        this.value = phoneNumber;
      }
    });
  }
  
  // Add ripple effect to buttons
  const formButtons = document.querySelectorAll('.page.login .group button');
  formButtons.forEach(button => {
    button.addEventListener('mousedown', function(e) {
      const x = e.pageX - this.offsetLeft;
      const y = e.pageY - this.offsetTop;
      
      const ripple = document.createElement('span');
      ripple.classList.add('ripple');
      ripple.style.top = y + 'px';
      ripple.style.left = x + 'px';
      
      this.appendChild(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 700);
    });
  });
}

/**
 * Footer Animation Functions
 */
function initFooterAnimations() {
  // Newsletter form animations
  const newsletterForm = document.querySelector('.newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const emailInput = this.querySelector('input[type="email"]');
      const submitBtn = this.querySelector('.newsletter-submit');
      
      if (emailInput && emailInput.checkValidity()) {
        // Success animation
        submitBtn.innerHTML = '<i class="fa fa-check"></i>';
        submitBtn.classList.add('success');
        
        // Reset after 2 seconds
        setTimeout(() => {
          submitBtn.innerHTML = 'Subscribe';
          submitBtn.classList.remove('success');
          emailInput.value = '';
        }, 2000);
      } else {
        // Error animation
        emailInput.classList.add('validation-error');
        setTimeout(() => {
          emailInput.classList.remove('validation-error');
        }, 1000);
      }
    });
  }
  
  // Social icons hover effect enhancement
  const socialIcons = document.querySelectorAll('.social-icon');
  socialIcons.forEach(icon => {
    icon.addEventListener('mouseover', function() {
      // Additional hover animations if needed beyond CSS
    });
  });
  
  // Footer reveal animation
  function animateFooterElements() {
    const elements = document.querySelectorAll('.footer-animate');
    elements.forEach(element => {
      const rect = element.getBoundingClientRect();
      const windowHeight = window.innerHeight;
      
      if (rect.top + rect.height / 2 < windowHeight) {
        element.classList.add('animated');
      }
    });
  }
  
  // Initialize footer animations
  const footerRows = document.querySelectorAll('.about .row');
  const boxShippers = document.querySelectorAll('.box-shipper');
  
  footerRows.forEach(row => row.classList.add('footer-animate'));
  boxShippers.forEach(box => box.classList.add('footer-animate'));
  
  // Run on load and scroll
  animateFooterElements();
  window.addEventListener('scroll', animateFooterElements);
}

/**
 * Admin Dashboard Animation Functions
 */
function initAdminDashboardAnimations() {
  // Stats counter animation
  const statsValues = document.querySelectorAll('.stats-value');
  statsValues.forEach(value => {
    const targetValue = parseInt(value.getAttribute('data-value') || '0');
    let currentValue = 0;
    const duration = 2000; // 2 seconds
    const stepTime = 50; // update every 50ms
    const totalSteps = duration / stepTime;
    const stepValue = targetValue / totalSteps;
    
    const countInterval = setInterval(() => {
      currentValue += stepValue;
      if (currentValue >= targetValue) {
        value.textContent = targetValue.toString();
        clearInterval(countInterval);
      } else {
        value.textContent = Math.floor(currentValue).toString();
      }
    }, stepTime);
  });
  
  // Data table animations
  const adminTables = document.querySelectorAll('.admin-table');
  adminTables.forEach(table => {
    // Add row highlight on hover if needed beyond CSS
    const tableRows = table.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
      row.addEventListener('mouseover', function() {
        // Additional hover animations if needed beyond CSS
      });
    });
    
    // Add sort functionality with animations
    const tableHeaders = table.querySelectorAll('th[data-sort]');
    tableHeaders.forEach(header => {
      header.addEventListener('click', function() {
        const sortOrder = this.getAttribute('data-sort-order') === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-sort-order', sortOrder);
        
        // Clear sort indicators from all headers
        tableHeaders.forEach(h => {
          h.classList.remove('sorting-asc', 'sorting-desc');
        });
        
        // Add sort indicator to clicked header
        this.classList.add(sortOrder === 'asc' ? 'sorting-asc' : 'sorting-desc');
        
        // Animation for sorting (could be implemented with an actual sort)
        tableRows.forEach(row => {
          row.style.opacity = '0';
          setTimeout(() => {
            row.style.opacity = '1';
          }, 300);
        });
      });
    });
  });
  
  // Admin form validation animations
  const adminFormInputs = document.querySelectorAll('.admin-form .form-control');
  adminFormInputs.forEach(input => {
    input.addEventListener('focus', function() {
      const formGroup = this.closest('.form-group');
      formGroup.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
      const formGroup = this.closest('.form-group');
      formGroup.classList.remove('focused');
      
      // Add validation animations
      if (this.checkValidity()) {
        formGroup.classList.add('validation-success');
        formGroup.classList.remove('validation-error');
      } else if (this.value !== '') {
        formGroup.classList.add('validation-error');
        formGroup.classList.remove('validation-success');
      }
    });
  });
  
  // Admin sidebar toggle animation
  const sidebarToggle = document.querySelector('.sidebar-toggle');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
      const sidebar = document.querySelector('.admin-sidebar');
      const content = document.querySelector('.admin-content');
      
      if (sidebar) {
        sidebar.classList.toggle('collapsed');
      }
      
      if (content) {
        content.classList.toggle('expanded');
      }
    });
  }
  
  // Filter/search animations
  const filterInputs = document.querySelectorAll('.admin-filter input, .admin-search input');
  filterInputs.forEach(input => {
    input.addEventListener('input', function() {
      // Add animation for filtering results
      const resultContainer = document.querySelector('.filter-results');
      if (resultContainer) {
        resultContainer.classList.add('filtering');
        setTimeout(() => {
          resultContainer.classList.remove('filtering');
        }, 500);
      }
    });
  });
}

/**
 * Guest Dashboard Animation Functions
 */
function initGuestDashboardAnimations() {
  // Profile image hover animation
  const profileImage = document.querySelector('.profile-image');
  if (profileImage) {
    profileImage.addEventListener('mouseover', function() {
      // Additional hover effects if needed beyond CSS
    });
  }
  
  // Order history table animations
  const orderTables = document.querySelectorAll('.order-table');
  orderTables.forEach(table => {
    // Add row highlight on hover if needed beyond CSS
    const tableRows = table.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
      row.addEventListener('click', function() {
        // Redirect to order details or expand row with details
        // This is just for animation - actual functionality handled by app
        this.classList.add('selected');
        setTimeout(() => {
          // Simulate redirect or expand
          this.classList.remove('selected');
        }, 300);
      });
    });
    
    // Add sort functionality with animations
    const tableHeaders = table.querySelectorAll('th[data-sort]');
    tableHeaders.forEach(header => {
      header.addEventListener('click', function() {
        const sortOrder = this.getAttribute('data-sort-order') === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-sort-order', sortOrder);
        
        // Clear sort indicators from all headers
        tableHeaders.forEach(h => {
          h.classList.remove('sorting-asc', 'sorting-desc');
        });
        
        // Add sort indicator to clicked header
        this.classList.add(sortOrder === 'asc' ? 'sorting-asc' : 'sorting-desc');
        
        // Animation for sorting (could be implemented with an actual sort)
        tableRows.forEach(row => {
          row.style.opacity = '0';
          setTimeout(() => {
            row.style.opacity = '1';
          }, 300);
        });
      });
    });
  });
  
  // Payment card hover effects
  const paymentCards = document.querySelectorAll('.payment-card');
  paymentCards.forEach(card => {
    card.addEventListener('mouseover', function() {
      // Apply 3D rotation effect
      this.style.transform = 'translateY(-5px) rotateY(5deg)';
    });
    
    card.addEventListener('mouseout', function() {
      this.style.transform = 'translateY(0) rotateY(0)';
    });
    
    // Delete payment card animation
    const deleteBtn = card.querySelector('.delete-card');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const parentCard = this.closest('.payment-card');
        parentCard.style.transform = 'translateX(100%)';
        parentCard.style.opacity = '0';
        
        // This is just for animation - actual deletion handled by app
        setTimeout(() => {
          parentCard.style.display = 'none';
        }, 300);
      });
    }
  });
  
  // Add new payment card animation
  const addCardBtn = document.querySelector('.add-card-btn');
  if (addCardBtn) {
    addCardBtn.addEventListener('click', function() {
      // This is just for animation - actual form display handled by app
      this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
      setTimeout(() => {
        this.innerHTML = 'Add New Card';
      }, 1000);
    });
  }
  
  // Address card animations
  const addressCards = document.querySelectorAll('.address-card');
  addressCards.forEach(card => {
    // Set as default address animation
    const defaultBtn = card.querySelector('.set-default');
    if (defaultBtn) {
      defaultBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove default badge from all cards
        document.querySelectorAll('.default-address-badge').forEach(badge => {
          badge.remove();
        });
        
        // Add default badge to this card
        const parentCard = this.closest('.address-card');
        const badgeHTML = '<span class="default-address-badge">Default</span>';
        parentCard.querySelector('.card-header').insertAdjacentHTML('beforeend', badgeHTML);
        
        // Highlight animation
        parentCard.classList.add('highlight');
        setTimeout(() => {
          parentCard.classList.remove('highlight');
        }, 1000);
      });
    }
    
    // Delete address animation
    const deleteBtn = card.querySelector('.delete-address');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const parentCard = this.closest('.address-card');
        parentCard.style.transform = 'translateX(100%)';
        parentCard.style.opacity = '0';
        
        // This is just for animation - actual deletion handled by app
        setTimeout(() => {
          parentCard.style.display = 'none';
        }, 300);
      });
    }
  });
  
  // Review card animations
  const reviewCards = document.querySelectorAll('.review-card');
  reviewCards.forEach(card => {
    // Highlight helpful reviews
    const helpfulBtn = card.querySelector('.mark-helpful');
    if (helpfulBtn) {
      helpfulBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Update helpful count
        const helpfulCount = this.querySelector('.helpful-count');
        if (helpfulCount) {
          const currentCount = parseInt(helpfulCount.textContent);
          helpfulCount.textContent = (currentCount + 1).toString();
          
          // Animate the count change
          helpfulCount.classList.add('count-changed');
          setTimeout(() => {
            helpfulCount.classList.remove('count-changed');
          }, 1000);
        }
      });
    }
  });
  
  // Wishlist item animations
  const wishlistItems = document.querySelectorAll('.wishlist-item');
  wishlistItems.forEach(item => {
    // Add to cart animation
    const addToCartBtn = item.querySelector('.add-to-cart-btn');
    if (addToCartBtn) {
      addToCartBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adding...';
        
        // Simulate add to cart animation
        setTimeout(() => {
          this.innerHTML = 'Added to Cart';
          this.classList.add('added');
          
          // Update cart icon in header
          const cartIcon = document.querySelector('.cart-icon');
          if (cartIcon) {
            cartIcon.classList.add('pulse');
            setTimeout(() => {
              cartIcon.classList.remove('pulse');
            }, 1000);
          }
        }, 1000);
      });
    }
    
    // Remove from wishlist animation
    const removeBtn = item.querySelector('.remove-wishlist');
    if (removeBtn) {
      removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const parentItem = this.closest('.wishlist-item');
        parentItem.style.transform = 'translateX(100%)';
        parentItem.style.opacity = '0';
        
        // This is just for animation - actual removal handled by app
        setTimeout(() => {
          parentItem.style.display = 'none';
        }, 300);
      });
    }
  });
  
  // Guest sidebar toggle animation
  const guestSidebarToggle = document.querySelector('.guest-sidebar-toggle');
  if (guestSidebarToggle) {
    guestSidebarToggle.addEventListener('click', function() {
      const sidebar = document.querySelector('.guest-sidebar');
      const content = document.querySelector('.guest-content');
      
      if (sidebar) {
        sidebar.classList.toggle('collapsed');
      }
      
      if (content) {
        content.classList.toggle('expanded');
      }
    });
  }
}

/**
 * Smooth Scroll Function
 */
function initSmoothScroll() {
  // Get all anchor links
  const anchorLinks = document.querySelectorAll('a[href*="#"]:not([href="#"])');
  
  anchorLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      // Only process if the link is to the same page
      if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
          location.hostname === this.hostname) {
        
        // Get the target element
        let target = document.querySelector(this.hash);
        if (!target) {
          target = document.querySelector('[name=' + this.hash.slice(1) + ']');
        }
        
        if (target) {
          e.preventDefault();
          
          // Calculate position
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
          const offsetPosition = targetPosition - 80;
          
          // Smooth scroll
          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });
          
          return false;
        }
      }
    });
  });
}

/**
 * Add Dynamic Animation Classes
 */
function addDynamicAnimationClasses() {
  // Create style element for dynamic CSS
  const style = document.createElement('style');
  style.type = 'text/css';
  style.innerHTML = `
    .shadow-nav-ready {
      transition: box-shadow 0.3s ease, transform 0.4s ease;
    }
    .shadow-nav {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .footer-animate {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .footer-animate.animated {
      opacity: 1;
      transform: translateY(0);
    }
    @keyframes wobble {
      0%, 100% { transform: rotate(0); }
      20% { transform: rotate(-10deg); }
      40% { transform: rotate(5deg); }
      60% { transform: rotate(-5deg); }
      80% { transform: rotate(3deg); }
    }
    .cart-wobble {
      animation: wobble 0.8s ease;
    }
    
    /* Login Form Dynamic Styles */
    .page.login .group.focused {
      transform: translateY(-5px);
    }
    .page.login .group label.active {
      transform: translateY(-3px) scale(0.9);
      color: #3b82f6;
    }
    .ripple {
      position: absolute;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      transform: scale(0);
      animation: ripple 0.7s linear;
      pointer-events: none;
      width: 200px;
      height: 200px;
      margin-top: -100px;
      margin-left: -100px;
    }
  `;
  document.head.appendChild(style);
  
  // Add ready class for nav shadow transition
  const navbarHeader = document.querySelector('.navbar-header');
  if (navbarHeader) {
    navbarHeader.classList.add('shadow-nav-ready');
  }
} 
// Guest Dashboard Animations
$(document).ready(function() {
    // Check if we're on a guest dashboard page
    if (window.location.pathname.includes('/dashboard')) {
        initGuestAnimations();
    }

    function initGuestAnimations() {
        // Add form group index for staggered animations
        $('.form .form-group').each(function(index) {
            $(this).css('--form-index', index + 1);
        });

        // Add row index for staggered animations
        $('.box-dashboard .row').each(function(index) {
            $(this).css('--row-index', index + 1);
        });

        // Initialize DataTables with custom styling
        if ($.fn.dataTable) {
            $('.table-datatables').each(function() {
                var tableId = $(this).attr('id');
                
                if (!$.fn.DataTable.isDataTable('#' + tableId)) {
                    $(this).DataTable({
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Tìm kiếm...",
                            lengthMenu: "Hiển thị _MENU_ kết quả",
                            info: "Hiển thị _START_ đến _END_ của _TOTAL_ kết quả",
                            infoEmpty: "Hiển thị 0 đến 0 của 0 kết quả",
                            infoFiltered: "(lọc từ _MAX_ kết quả)",
                            paginate: {
                                first: "Đầu",
                                last: "Cuối",
                                next: "Sau",
                                previous: "Trước"
                            }
                        },
                        responsive: true,
                        pageLength: 10
                    });
                }
            });
        }

        // Button ripple effect
        $('.box-dashboard button').on('mousedown', function(e) {
            var $button = $(this);
            
            // Remove any existing ripples
            $button.find('.ripple').remove();
            
            var offset = $button.offset();
            var x = e.pageX - offset.left;
            var y = e.pageY - offset.top;
            
            var $ripple = $('<span class="ripple"></span>');
            $ripple.css({
                top: y + 'px',
                left: x + 'px'
            });
            
            $button.append($ripple);
            
            setTimeout(function() {
                $ripple.remove();
            }, 700);
        });

        // Quantity updater animations
        $('#up-count-product, #down-count-product').on('click', function() {
            var $this = $(this);
            var $counter = $this.siblings('#count-product');
            
            $counter.addClass('count-change');
            
            setTimeout(function() {
                $counter.removeClass('count-change');
            }, 300);
        });

        // Order card highlight effects
        $('[id^=btn-order-detail-guest]').on('click', function() {
            var $button = $(this);
            var $row = $button.closest('tr');
            
            $row.addClass('row-highlight');
            
            setTimeout(function() {
                $row.removeClass('row-highlight');
            }, 1000);
        });

        // Payment method selection enhancement
        $('.payment-option input[type="radio"]').on('change', function() {
            var $radio = $(this);
            var value = $radio.val();
            
            // Handle the payment selection
            if (value === 'vnpay') {
                $('#vnpay-info').slideDown(300);
            } else {
                $('#vnpay-info').slideUp(300);
            }
        });

        // Form validation visual feedback
        $('.form input:required').on('blur', function() {
            var $input = $(this);
            
            if ($input.val().trim() === '') {
                $input.addClass('invalid');
            } else {
                $input.removeClass('invalid').addClass('valid');
            }
        });

        // Password match validation
        $('#password-confirm').on('keyup', function() {
            var password = $('#password-new').val();
            var confirmPassword = $(this).val();
            
            if (password && confirmPassword) {
                if (password !== confirmPassword) {
                    $(this).addClass('invalid').removeClass('valid');
                } else {
                    $(this).removeClass('invalid').addClass('valid');
                }
            }
        });

        // Phone number formatting
        $('input[name="phone"]').on('input', function() {
            var phoneNumber = $(this).val().replace(/\D/g, '');
            
            // Format as XXX-XXX-XXXX or your preferred format
            if (phoneNumber.length > 0) {
                if (phoneNumber.length <= 4) {
                    phoneNumber = phoneNumber;
                } else if (phoneNumber.length <= 7) {
                    phoneNumber = phoneNumber.slice(0, 4) + '-' + phoneNumber.slice(4);
                } else {
                    phoneNumber = phoneNumber.slice(0, 4) + '-' + phoneNumber.slice(4, 7) + '-' + phoneNumber.slice(7, 11);
                }
                $(this).val(phoneNumber);
            }
        });

        // Add dynamic CSS styles
        addGuestStyles();
    }

    function addGuestStyles() {
        var style = document.createElement('style');
        style.type = 'text/css';
        style.innerHTML = `
            .ripple {
                position: absolute;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.7s linear;
                pointer-events: none;
                width: 120px;
                height: 120px;
                margin-top: -60px;
                margin-left: -60px;
            }
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
            .count-change {
                animation: pulse 0.3s ease;
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
            .row-highlight {
                background-color: rgba(59, 130, 246, 0.1) !important;
                transition: background-color 1s ease;
            }
            .invalid {
                border-color: #ef4444 !important;
                background-color: rgba(239, 68, 68, 0.05) !important;
            }
            .valid {
                border-color: #10b981 !important;
                background-color: rgba(16, 185, 129, 0.05) !important;
            }
            #vnpay-info {
                display: none;
            }
            .table-datatables td ion-icon {
                display: inline-flex;
                vertical-align: middle;
            }
        `;
        document.head.appendChild(style);
    }
}); 
