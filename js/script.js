document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const bookCards = document.querySelectorAll(".book-card");

    searchInput.addEventListener("keyup", function () {
        const keyword = searchInput.value.toLowerCase();

        bookCards.forEach(card => {
            const title = card.querySelector(".card-title").innerText.toLowerCase();

            card.style.display = title.includes(keyword) ? "block" : "none";
        });
    });
});
