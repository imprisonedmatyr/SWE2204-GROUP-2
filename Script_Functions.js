let featuredOffset = 10;
let mostVisitedOffset = 10;
const limit = 10;

function loadMoreBooks(section) {
    let offset = section === 'featured' ? featuredOffset : mostVisitedOffset;

    fetch(`?section=${section}&limit=${limit}&offset=${offset}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const bookList = document.getElementById(`${section}-books`);
                data.forEach(book => {
                    const bookItem = `
                                <div class="book-item">
                                    <div class="book-coverpage">
                                        <img src="/VL/img/Book_Covers/${book.IMAGE}" alt="${book.TITLE} book cover image">
                                    </div>
                                    <div class="book-title">
                                        <a class="title-anchor" href="bookinfo.php?book_id=${book.book_id}">${book.TITLE}</a>
                                    </div>
                                </div>`;
                    bookList.insertAdjacentHTML('beforeend', bookItem);
                });

                // Update offsets
                if (section === 'featured') {
                    featuredOffset += limit;
                } else {
                    mostVisitedOffset += limit;
                }
            } else {
                document.querySelector(`.show-more-btn[onclick*="${section}"]`).style.display = 'none';
            }
        })
        .catch(error => console.error('Error loading more books:', error));
}

function toggleDropdown() {
    document.querySelector('.user-icon').classList.toggle('active');
}

// Close the dropdown if clicked outside
window.addEventListener('click', function (e) {
    if (!document.querySelector('.user-icon').contains(e.target)) {
        document.querySelector('.user-icon').classList.remove('active');
    }
});
