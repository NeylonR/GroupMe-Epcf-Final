// js pour le burger button de la version mobile
const getBurgerButton = document.getElementById('burgerButton');
const getCloseButton = document.getElementById('closeButton');
const getNavbar = document.querySelector('nav');
const getBody = document.querySelector('body');

function toggleNavbar(){
    getNavbar.classList.toggle('active');
    getBody.classList.toggle('noScroll');
}

// lors du click sur le burger button > rajout si non présent/retrait si déjà présent de la classe "active" et noScroll sur le body pour empecher le scroll d l'arriere plan
getBurgerButton.addEventListener('click', function() {
    toggleNavbar();
    // getNavbar.dataset.status = (getNavbar.dataset.status === "default") ? "mobile" : "default";
})

getCloseButton.addEventListener('click', function() {
    toggleNavbar();
})