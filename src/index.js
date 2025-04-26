document.getElementById("searchInput").addEventListener("input", function () {
  const searchValue = this.value.toLowerCase();
  const rows = document.querySelectorAll("tbody tr");

  let visibleCount = 0;
  rows.forEach((row) => {
    const text = row.textContent.toLowerCase();
    if (text.includes(searchValue)) {
      row.classList.remove("hidden");
      visibleCount++;
    } else {
      row.classList.add("hidden");
    }
  });

  document.getElementById("countResults").textContent = visibleCount;
});

// function delete
function confirmDelete(id) {
  if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
    window.location.href = "prosesData.php?id=" + id;
  }
  return false;
}
