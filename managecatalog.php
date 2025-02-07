<?php
session_start();

require 'db_connect.php';

// Fetch the latest 10 books
$stmt = $connection->prepare('SELECT * FROM BOOKS ORDER BY book_id DESC LIMIT 10');
$stmt->execute();
$result = $stmt->get_result();

// fetch the latest 10 chapters
$st = $connection->prepare('SELECT * from BOOKS join CONTENT on BOOKS.book_id=CONTENT.book_id order by updated_at desc');
$st->execute();
$result0 = $st->get_result();

// Fetch all books for dropdowns
$stmt1 = $connection->prepare('SELECT * FROM BOOKS ORDER BY title');
$stmt1->execute();
$result1 = $stmt1->get_result();

$p = $connection->prepare('SELECT Chapter_title from CONTENT where book_id = ?');
$p->bind_param("s", $book_id);
$p->execute();
$p1 = $p->get_result();

$p2 = $connection->prepare("SELECT * FROM BOOKS b join CONTENT c on b.book_id=c.book_id group by b.book_id ORDER BY title");
$p2->execute();
$p3 = $p2->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Catalog</title>
    <link rel="stylesheet" href="/VL/css/managecatalog.css?v=<?php echo time(); ?>">
    <link rel="icon" href="/VL/img/favicon/sk.ico">
</head>

<body>
    <header>
        <?php include 'header_admin.php'; ?>
    </header>

    <main>
        <section class="catalog-section">
            <div class="myOhmy">
                <h2>Manage Catalog</h2>
                <div id="feedback"
                    class="<?php echo isset($_SESSION['error_message']) ? 'error-message' : (isset($_SESSION['success_message']) ? 'success-message' : ''); ?>"
                    style="display: <?php echo (isset($_SESSION['error_message']) || isset($_SESSION['success_message'])) ? 'block' : 'none'; ?>;">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo htmlspecialchars($_SESSION['error_message']);
                        unset($_SESSION['error_message']); // Clear the error message after displaying it
                    } elseif (isset($_SESSION['success_message'])) {
                        echo htmlspecialchars($_SESSION['success_message']);
                        unset($_SESSION['success_message']); // Clear the success message after displaying it
                    }
                    ?>
                </div>
            </div>
            <div class="options">
                <button onclick="toggleForm('add-book-form')">Add New Book</button>
                <button onclick="toggleForm('update-book-form')">Update Book Details</button>
                <button onclick="toggleForm('upload-chapter-form')">Upload New Chapter</button>
                <button onclick="toggleForm('update-chapter-form')">Update Chapter Details</button>
            </div>

            <!-- Form to Add New Book -->
            <form class="form" id="add-book-form" action="add_book.php" method="POST" enctype="multipart/form-data"
                style="display: none;">
                <h3>Add New Book</h3>

                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>

                <label for="book_cover">Book Cover:</label>
                <input type="file" id="book_cover" name="book_cover" required accept="image/*">

                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>

                <label for="genre">Genre:</label>
                <input type="text" id="genre" name="genre" required>

                <label for="year">Publication Year:</label>
                <input type="number" id="year" name="year" required min="1000" max="9999">

                <label for="summary">Short Description:</label>
                <input type="file" id="summary" name="summary" required accept=".pdf,.txt,.epub">

                <button type="submit" class="add-book-btn">Add Book</button>
            </form>


            <!-- Form to Update Book Details -->
            <form class="form" id="update-book-form" action="update_book.php" method="POST" style="display: none;">
                <h3>Update Book Details</h3>
                <label for="book_id_update">Select Book:</label>
                <select id="book_id_update" name="book_id_update" required>
                    <option value="">--Select a Book--</option>
                    <?php if ($result1->num_rows > 0): ?>
                        <?php while ($row = $result1->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['book_id']); ?>">
                                <?php echo htmlspecialchars($row['TITLE']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">-- No books to display --</option>
                    <?php endif; ?>
                </select>

                <label for="title">Title:</label>
                <input type="text" id="title_update" name="title" required>

                <label for="author">Author:</label>
                <input type="text" id="author_update" name="author" required>

                <label for="book_cover_update">Book Cover:</label>
                <input type="file" id="book_cover_update" name="book_cover" accept="image/*">

                <label for="category">Category:</label>
                <input type="text" id="category_update" name="category" required>

                <label for="genre">Genre:</label>
                <input type="text" id="genre_update" name="genre" required>

                <label for="year">Publication Year:</label>
                <input type="number" id="year_update" name="year" required>

                <label for="summary">Short Description:</label>
                <input type="file" id="summary_update" name="summary" required></input>

                <button type="submit" class="update-book-btn">Update Book</button>
            </form>

            <!-- Form to Upload New Chapter -->
            <form class="form" id="upload-chapter-form" action="upload_chapter.php" method="POST" style="display: none;"
                enctype="multipart/form-data">
                <h3>Upload New Chapter</h3>
                <label for="book_id_chapter_upload">Select Book:</label>
                <select id="book_id_chapter_upload" name="book_id" required>
                    <option value="">--Select a Book--</option>
                    <?php
                    $pst = $connection->prepare('SELECT * FROM BOOKS ORDER BY title');
                    $pst->execute();
                    $rst = $pst->get_result();
                    ?>
                    <?php if ($rst->num_rows > 0): ?>
                        <?php while ($r = $rst->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($r['book_id']); ?>">
                                <?php echo htmlspecialchars($r['TITLE']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">-- No books to display --</option>
                    <?php endif; ?>
                </select>

                <label for="chapter_title_upload">Chapter Title:</label>
                <input type="text" id="chapter_title_upload" name="chapter_title" required>

                <label for="chapter_content_upload">Chapter Content:</label>
                <input type="file" id="chapter_content_upload" name="chapter_content" required>

                <button type="submit" class="upload-chapter-btn">Upload Chapter</button>
            </form>

            <!-- Form to Update existing Chapter details -->
            <form class="form" id="update-chapter-form" action="update_chapter.php" method="POST" style="display: none;"
                enctype="multipart/form-data">
                <h3>Update Chapter</h3>

                <label for="book_id_chapter_upload">Select Book:</label>
                <select id="book_id_chapter_upload" name="book_id" required onchange="loadChapters(this.value)">
                    <option value="">--Select a Book--</option>
                    <?php if ($p3->num_rows > 0): ?>
                        <?php while ($p4 = $p3->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($p4['book_id']); ?>">
                                <?php echo htmlspecialchars($p4['TITLE']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">-- No books to display --</option>
                    <?php endif; ?>
                </select>

                <label for="chapter_id_update">Select Chapter:</label>
                <select id="chapter_id_update" name="chapter_id" required>
                    <option value="">--Select a Chapter to Update--</option>
                    <!-- Chapters will be populated here via JavaScript -->
                </select>

                <label for="chapter_title">Chapter Title:</label>
                <input type="text" id="chapter_title_update" name="chapter_title" required>

                <label for="chapter_content">Chapter Content:</label>
                <input type="file" id="chapter_content_update" name="chapter_content">

                <button type="submit" class="update-chapter-btn">Update Chapter</button>
            </form>

            <table class="catalog-table">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['TITLE']); ?></td>
                                <td><?php echo htmlspecialchars($row['AUTHOR']); ?></td>
                                <!-- <td>
                                    <button class="edit-btn" href="toggleform(update-book-form)">Edit</button>
                                    <button class="delete-btn">Delete</button>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="recents-section">
            <h2>Recent Activities</h2>
            <table class="recents-table">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Activity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result0 && $result0->num_rows > 0) {
                        while ($row0 = $result0->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row0['book_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row0['Chapter_title']);
                            if (!empty($row0['TITLE'])) {
                                echo " \"" . htmlspecialchars($row0['TITLE']) . "\"";
                            }
                            if (!empty($row0['Chapter_title'])) {
                                echo " - Chapter: \"" . htmlspecialchars($row0['Chapter_title']) . "\"";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row0['updated_at']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No recent activities found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script>
        function toggleForm(formId) {
            const forms = document.querySelectorAll('.form');
            forms.forEach(form => {
                form.style.display = (form.id === formId && form.style.display === 'none') ? 'block' : 'none';
            });
        }
        // // Fetch the book details from the server and populate the update form
        // function editBook(bookId) {
        //     // Display the update form
        //     toggleForm('update-book-form');

        //     // Fetch the book details via AJAX
        //     fetch('get_book.php?book_id=' + bookId)
        //         .then(response => response.json())
        //         .then(data => {
        //             // Populate the update form with the fetched data
        //             document.getElementById('book_id_update').value = data.book_id;
        //             document.getElementById('title_update').value = data.title;
        //             document.getElementById('author_update').value = data.author;
        //             document.getElementById('category_update').value = data.category;
        //             document.getElementById('genre_update').value = data.genre;
        //             document.getElementById('year_update').value = data.year;
        //             // Add more fields as necessary
        //         })
        //         .catch(error => {
        //             console.error('Error fetching book details:', error);
        //             alert('Failed to load book details.');
        //         });
        // }

        // function to delete a book from the server/database
        // function deleteBook(bookId) {
        //     if (confirm('Are you sure you want to delete this book?')) {
        //         // Send a request to delete the book via AJAX
        //         fetch('delete_book.php?book_id=' + bookId, {
        //                 method: 'POST'
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.success) {
        //                     alert('Book deleted successfully.');
        //                     location.reload(); // Refresh the page to update the book list
        //                 } else {
        //                     alert('Failed to delete the book.');
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error deleting book:', error);
        //                 alert('Error occurred while deleting the book.');
        //             });
        //     }
        // }

        // Function to load chapters based on selected book
        async function loadChapters(bookId) {

            if (bookId) {

                const chapterUpdate = document.getElementById('chapter_id_update');
                chapterUpdate.innerHTML = '';
                const response = await fetch('get_chapters.php?book_id=' + bookId);
                const chapters = await response.json();

                chapters.forEach(chapter => {
                    const option = document.createElement('option');

                    option.value = chapter.chapter_id;
                    option.textContent = chapter.chapter_title;

                    chapterUpdate.appendChild(option);
                })

                // fetch('get_chapters.php?book_id=' + bookId)
                //     .then(response => response.json())
                //     .then(data => {
                //         const chapterSelect = document.getElementById('chapter_id_update');
                //         chapterSelect.innerHTML = '<option value="">--Select a Chapter to Update--</option>'; // Reset options
                //         data.forEach(chapter => {
                //             const option = document.createElement('option');
                //             option.value = chapter.chapter_id; // Assuming chapter_id exists in your database
                //             option.textContent = chapter.chapter_title; // Assuming chapter_title exists in your database
                //             chapterSelect.appendChild(option);
                //         });
                //     })
                //     .catch(error => {
                //         console.error('Error fetching chapters:', error);
                //     });
            } else {
                // Reset chapter select if no book is selected
                document.getElementById('chapter_id_update').innerHTML = '<option value="">--Select a Chapter to Update--</option>';
            }
        }
    </script>
</body>

</html>
<?php $connection->close(); ?>