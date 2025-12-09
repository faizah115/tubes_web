// -----------------------------------------------------
//  MENUNGGU SELURUH HALAMAN SELESAI DIMUAT
// -----------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {

    // -----------------------------------------------------
    //  MENGAMBIL ELEMEN INPUT SEARCH DAN SEMUA KARTU BUKU
    // -----------------------------------------------------
    const searchInput = document.getElementById("searchInput");
    const bookCards = document.querySelectorAll(".book-card");

    // -----------------------------------------------------
    //  EVENT: SAAT USER MENGETIK DI SEARCH BAR
    // -----------------------------------------------------
    searchInput.addEventListener("keyup", function () {

        // ambil keyword yang diketik user dan ubah ke huruf kecil
        const keyword = searchInput.value.toLowerCase();

        // -----------------------------------------------------
        //  LOOP SETIAP CARD BUKU UNTUK DICEK APAKAH COCOK
        // -----------------------------------------------------
        bookCards.forEach(card => {

            // ambil judul buku dari card, ubah ke huruf kecil
            const title = card.querySelector(".card-title").innerText.toLowerCase();

            // -----------------------------------------------------
            //  TAMPILKAN / SEMBUNYIKAN CARD BERDASARKAN KEYWORD
            // -----------------------------------------------------
            card.style.display = title.includes(keyword) ? "block" : "none";
        });
    });
});

