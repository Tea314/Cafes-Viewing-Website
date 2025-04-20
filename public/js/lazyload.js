document.addEventListener("DOMContentLoaded", function () {
    let page = 1;
    const loadMoreBtn = document.getElementById("loadMore");
    const modalContainer = document.getElementById("modal-container"); 
    const container = document.querySelector("#cafe-list .row"); 

    document.querySelectorAll('.modal').forEach(modalElement => {
        new bootstrap.Modal(modalElement);
    });

    loadMoreBtn.addEventListener("click", function () {
        page++;
        fetch(`../app/controllers/LazyLoadControllers.php?page=${page}`) 
            .then(response => response.json())
            .then(cafes => {
                cafes.forEach(cafe => {
                    const div = document.createElement("div");
                    div.classList.add("col-12", "col-md-6", "col-lg-4", "col-xl-3", "d-flex", "justify-content-center");

                    let imageHtml = cafe.images.length > 0 
                        ? `<img src="${cafe.images[0]}" class="card-img-top align-center p-2 rounded-5" 
                               style="width: 100%; object-fit: cover; height: 24em;" alt="${cafe.name}">`
                        : `<img src="assets/default.png" class="card-img-top align-center p-2 rounded-5"
                               style="width: 100%; object-fit: cover; height: 24em;" alt="Default Image">`;

                    let tagsHtml = '';
                    if (cafe.tags && cafe.tags.length > 0) {
                        tagsHtml = '<div class="mt-2">';
                        cafe.tags.slice(0, 3).forEach(tag => {
                            tagsHtml += `<span class="badge bg-info bg-gradient">${tag.name}</span> `;
                        });
                        if (cafe.tags.length > 3) {
                            tagsHtml += `<span class="badge bg-info bg-gradient bg-opacity-75">+${cafe.tags.length - 3}</span>`;
                        }
                        tagsHtml += '</div>';
                    }

                    div.innerHTML = `
                        <div class="card card-cafe shadow-lg p-3 rounded-4 border-0 w-100" 
                             data-bs-toggle="modal" data-bs-target="#cafeModal${cafe.index}">
                            ${imageHtml}
                            <div class="card-body">
                                <h5 class="card-title pt-0">${cafe.name}</h5>
                                <p class="card-text">${cafe.description}</p>
                                ${tagsHtml}
                            </div>
                        </div>
                    `;

                    container.appendChild(div);
                    modalContainer.insertAdjacentHTML('beforeend', cafe.modalHtml);
                    const modalElement = document.getElementById(`cafeModal${cafe.index}`);
                    if (modalElement) {
                        new bootstrap.Modal(modalElement);
                    }
                });
                if (cafes.length < 3) {
                    loadMoreBtn.style.display = "none";
                }
            })
            .catch(error => console.error("Error loading more cafes:", error));
    });
});
