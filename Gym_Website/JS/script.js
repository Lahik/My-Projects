let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');
let profile = document.querySelector('.header .profile');
let user = document.querySelector('#user-btn');

menu.onclick = () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
    profile.classList.remove('active');
};

user.onclick = () => {
    profile.classList.toggle('active');
    navbar.classList.remove('active');
};

window.onscroll = () =>{
    navbar.classList.remove('active');
    profile.classList.remove('active');
    menu.classList.remove('fa-times');
}

var swiper = new Swiper(".home-slider", {
    spaceBetween: 20,
    effect: "fade",
    grabCursor: true,
    loop: true,
    centeredSlides: true,
    autoplay: {
        delay: 3000,
    },
    speed: 1500,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});

// function closeMessage(element) {
//     element.classList.add('hide'); 
//     setTimeout(function() {
//         element.remove(); 
//     }, 5000);
// }

window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");

    loader.classList.add("loader-hidden");

    loader.addEventListener("transitionend", () => {
        document.body.removeChild("loader");
    })
})

