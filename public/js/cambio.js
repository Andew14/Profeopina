// public/js/inicio_img_cambio.js

document.addEventListener('DOMContentLoaded', function() {
    const logoImage = document.getElementById('subtitulo');
    const currentLanguage = localStorage.getItem('language') || 'es';
    changeLogo(currentLanguage);

    // Function to change the language and logo
    window.changeLanguage = function(language) {
        localStorage.setItem('language', language);
        changeLogo(language);
        // Redirect to the language-specific URL
        window.location.href = `/${language}`;
    };

    // Function to change the logo based on the language
    function changeLogo(language) {
        if (language === 'en') {
            logoImage.src = '/logos/Logo_subtitleen.svg';
        } else if (language === 'es') {
            logoImage.src = '/logos/Logo_subtitle.svg';
        }
    }
});
