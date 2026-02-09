document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const logoToggle = document.getElementById("logoToggle");

    logoToggle.addEventListener("click", function () {

        if (window.innerWidth <= 768) {
            sidebar.classList.toggle("mobile-open");
        }

    });

});
