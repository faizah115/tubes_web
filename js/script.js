document.querySelectorAll("#reviewTable tr").forEach(row => setupRow(row));
function setupRow(row) {
    const editBtn = row.querySelector(".edit-btn");
    const deleteBtn = row.querySelector(".delete-btn");
    deleteBtn.onclick = () => row.remove();
    editBtn.onclick = () => {

        const namaCell = row.cells[0];
        const fotoCell = row.cells[1];
        const komentarCell = row.cells[2];
        const newNama = prompt("Edit Nama:", namaCell.innerText);
        if (newNama) namaCell.innerText = newNama;
        const newKomentar = prompt("Edit Komentar:", komentarCell.innerText);
        if (newKomentar) komentarCell.innerText = newKomentar;
        const ganti = confirm("Apakah Anda ingin mengganti foto?");
        if (!ganti) return; 
        const input = document.createElement("input");
        input.type = "file";
        input.accept = "image/*";
        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                fotoCell.innerHTML = `<img src="${url}" width="150" class="rounded">`;
            }
        };
        input.click();
    };
}
