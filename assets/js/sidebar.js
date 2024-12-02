document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const toggleButton = document.getElementById("toggle-sidebar");

    toggleButton.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
        mainContent.classList.toggle("collapsed");
    });
});
