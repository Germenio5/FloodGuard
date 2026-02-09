const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggleBtn");
const main = document.querySelector("main");

toggleBtn.addEventListener("click", () => {

    // Desktop behavior
    if (window.innerWidth > 768) {
        sidebar.classList.toggle("collapsed");
        main.classList.toggle("collapsed");
    }

    // Mobile behavior
    else {
        sidebar.classList.toggle("mobile-open");
    }
});
