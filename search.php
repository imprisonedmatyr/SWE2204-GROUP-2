<?php
session_start();
require 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="icon" href="/VL/img/favicon/sk.ico">
    <link rel="stylesheet" href="/VL/css/styles2.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <?php include 'header_client.php'; ?>
    </header>

    <div class="search-bar">
        <div>
            <form id="form">
                <div class="sb">

                    <input type="text" name="title" id="searchInput" placeholder="Search for books, authors, or category..."
                        class="search-input">

                    <input type="text" name="author" placeholder="Author">

                    <select name="genre">
                        <option value="">Category</option>
                        <?php
                        $cat = $connection->prepare("SELECT DISTINCT CATEGORY FROM books");
                        $cat->execute();
                        $dog = $cat->get_result();
                        ?>
                        <?php while ($rat = $dog->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($rat['CATEGORY']); ?>">
                                <?php echo htmlspecialchars($rat['CATEGORY']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                </div>
                <div style="padding-top: 20px; justify-content: center;" class="sb">
                    <input type="submit" value="Search" class="submit-btn" id="submit">
                </div>

            </form>
        </div>
    </div>
    <h2 style="color:aliceblue; text-align:center;">Search Results</h2>

    <div class="search-results">
        <div class="book-list" id="results"></div>
    </div>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script>
        document.getElementById('form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(event.target);

            const response = await fetch('searchResult.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)

            });
            document.getElementById('results').innerHTML = '';

            const data = await response.json();
            console.log(data);

            if (data.message) {
                document.getElementById('results').innerHTML = data.message
            } else {


                data.forEach(book => {
                    const bookDiv = document.createElement('div');
                    bookDiv.classList.add('book-item');

                    const coverPage = document.createElement('div');
                    coverPage.classList.add('book-coverpage');
                    const coverImage = document.createElement('img');
                    coverImage.src = '/VL/img/Book_Covers/' + book.IMAGE;
                    coverImage.alt = book.TITLE + 'book cover image';
                    coverPage.appendChild(coverImage);
                    bookDiv.appendChild(coverPage);

                    const titleDiv = document.createElement('div');
                    titleDiv.classList.add('book-title');
                    const titleLink = document.createElement('a');
                    titleLink.href = 'bookinfo.php?book_id=' + book.book_id;
                    titleLink.textContent = book.TITLE;
                    titleDiv.appendChild(titleLink);
                    bookDiv.appendChild(titleDiv);

                    document.getElementById('results').appendChild(bookDiv);
                });
            }
        });
    </script>

</body>

</html>