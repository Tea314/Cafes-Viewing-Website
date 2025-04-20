function toggleMenu() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    if (sidebar.classList.contains("active")) {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    } else {
        document.getElementById
        sidebar.classList.add("active");
        overlay.classList.add("active");
    }
}
document.getElementById("overlay").addEventListener("click", function () {
    document.getElementById("sidebar").classList.remove("active");
    this.classList.remove("active");
});


