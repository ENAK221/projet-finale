let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
    profile.classList.toggle('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    profile.classList.remove('active');
}

window.onscroll = () =>{
    profile.classList.remove('active');
    navbar.classList.remove('active');
}

subImages = document.querySelectorAll('.quick-view .box .image-container .small-image img');
mainImage = document.querySelector('.quick-view .box .image-container .big-image img');

subImages.forEach(images => {
    images.onclick = () =>{
        let src = images.getAttribute('src');
        mainImage.src = src;
    }
});



    document.addEventListener('DOMContentLoaded', function() {
        // Sélectionner l'élément du message
        const successMessage = document.getElementById('success-message');

        // Vérifier si l'élément existe
        if (successMessage) {
            // Retirer le message après 4 secondes
            setTimeout(function() {
                successMessage.style.opacity = '0';
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 500); // Temps pour faire disparaître le message
            }, 4000); // 4 secondes
        }
    });

