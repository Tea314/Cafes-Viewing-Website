
document.addEventListener("DOMContentLoaded", function () {
    const sortByNameBtn = document.getElementById("sortByName");

    sortByNameBtn.addEventListener("click", function () {
        const container = document.querySelector("#cafe-list .row");
        const cafes = Array.from(container.children); 

        cafes.sort((a, b) => {
            const nameA = a.querySelector(".card-title").textContent.trim().toLowerCase();
            const nameB = b.querySelector(".card-title").textContent.trim().toLowerCase();
            return nameA.localeCompare(nameB); 
        });
        container.innerHTML = "";
        cafes.forEach(cafe => container.appendChild(cafe));
    });
});
