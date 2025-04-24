const searchInput = document.getElementById('searchInput');
const dataTable = document.getElementById('dataTable');
const countResults = document.getElementById('countResults');

searchInput.addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = dataTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    let visibleCount = 0;

    for (let i = 0; i < rows.length; i++) {
        const nameCell = rows[i].getElementsByTagName('td')[1];
        const provinceCell = rows[i].getElementsByTagName('td')[4];

        if (nameCell && provinceCell) {
            const nameText = nameCell.textContent || nameCell.innerText;
            const provinceText = provinceCell.textContent || provinceCell.innerText;

            if (nameText.toLowerCase().includes(searchTerm) ||
                provinceText.toLowerCase().includes(searchTerm)) {
                rows[i].style.display = '';
                visibleCount++;
            } else {
                rows[i].style.display = 'none';
            }
        }
    }

    countResults.textContent = visibleCount;
});