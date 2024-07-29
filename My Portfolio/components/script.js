gsap.registerPlugin(ScrollTrigger);
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close'),
      navLinks = document.querySelectorAll('.nav-link'),
      scroll_up = document.getElementById('scroll-up')

//? menu show      
if(navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
        scroll_up.classList.add('hide-scroll-up');
    })
}

//? menu hidden
if(navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
        scroll_up.classList.remove('hide-scroll-up');
    })
}

//? nav link:active menu hidden 
if(navLinks.length > 0) {
    navLinks.forEach(navLink => {
        navLink.addEventListener('click', () => {
            navMenu.classList.remove('show-menu');
            scroll_up.classList.remove('hide-scroll-up');
        });
    });
}

//? loader
window.addEventListener("load", () => {
    const loader = document.querySelector(".loader-container");

    loader.classList.add("loader-hidden");

    loader.addEventListener("transitionend", () => {
        document.body.removeChild(loader);
    })
})

function scrollUp() {
    const scrollUp = document.getElementById('scroll-up');
    if(this.scrollY >= 560) scrollUp.classList.add('show-scroll'); else scrollUp.classList.remove('show-scroll');
}
window.addEventListener('scroll', scrollUp)

// HOME
TweenMax.from('.home-title', 1, {delay: .4, opacity: 0, y: 20, ease:Linear.easeNone})
TweenMax.from('.home-subtitle', 1, {delay: .5, opacity: 0, y: 20, ease:Linear.easeNone})
TweenMax.from('.home-description', 1, {delay: .6, opacity: 0, y: 20, ease:Linear.easeNone})
TweenMax.from('.home-button', 1, {delay: .7, opacity: 0, y: 20, ease:Linear.easeNone})
TweenMax.from('.home-img', 1, {delay: .7, opacity: 0, x: 20, ease:Linear.easeNone})

// ABOUT
gsap.from('.about-image', {duration: 1, delay: .4, opacity: 0, x: -50, ease: 'linear',
    scrollTrigger: {
      trigger: '.about-image',
      start: 'top 80%', 
      toggleActions: 'play none none none'
    }
});
  
gsap.from('.cv-description', {duration: .7, delay: .5, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.cv-description',
        start: 'top 80%', 
        toggleActions: 'play none none none'
    }
});
  
gsap.from('.stats', {duration: .7, delay: .6, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.stats',
        start: 'top 90%', 
        toggleActions: 'play none none none'
    }
});
  
gsap.from('.download-button', {duration: .7, delay: .7, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.download-button',
        start: 'bottom 90%', 
        toggleActions: 'play none none none'
    }
});
  

// Education
gsap.from('.zahira', {duration: .7, delay: .4, opacity: 0, x: -25, ease: 'linear',
    scrollTrigger: {
        trigger: '.zahira',
        start: 'top 80%', 
        toggleActions: 'play none none none'
    }
});
gsap.from('.azhar', {duration: .7, delay: .4, opacity: 0, x: 25, ease: 'linear',
    scrollTrigger: {
        trigger: '.azhar',
        start: 'top 80%', 
        toggleActions: 'play none none none'
    }
});
gsap.from('.icbt', {duration: .7, delay: .4, opacity: 0, x: -25, ease: 'linear',
    scrollTrigger: {
        trigger: '.icbt',
        start: 'top 80%', 
        toggleActions: 'play none none none'
    }
});

// Portfolio
gsap.from('.portfolio-content', {duration: .7, delay: .4, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.portfolio-content',
        start: 'top 80%', 
        toggleActions: 'play none none none'
    }
});

// Contact 
gsap.from('.contact-content', {duration: .8, delay: .4, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.contact-content',
        start: 'top 100%', 
        toggleActions: 'play none none none'
    }
});
gsap.from('.name', {duration: .7, delay: .4, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.name',
        start: 'top 80%', 
        toggleActions: 'play none none none'
    }
});
gsap.from('.email', {duration: .7, delay: .4, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.email',
        start: 'top 90%', 
        toggleActions: 'play none none none'
    }
});
gsap.from('.form-message', {duration: .7, delay: .4, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.form-message',
        start: 'top 90%', 
        toggleActions: 'play none none none'
    }
});
gsap.from('.message-button', {duration: .7, delay: .4, opacity: 0, y: 20, ease: 'linear',
    scrollTrigger: {
        trigger: '.message-button',
        start: 'top 100%', 
        toggleActions: 'play none none none'
    }
});


