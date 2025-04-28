<?php
session_start();

require 'E-Library/db_connect.php';

// Check if it's an AJAX request for loading more books
if (isset($_GET['section']) && isset($_GET['limit']) && isset($_GET['offset'])) {
    $section = $_GET['section'];
    $limit = intval($_GET['limit']);
    $offset = intval($_GET['offset']);

    if ($section === 'featured') {
        $stmt = $database->prepare("SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM books ORDER BY book_id ASC LIMIT ? OFFSET ?");
    } elseif ($section === 'most-visited') {
        $stmt = $database->prepare("SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM books WHERE visits >= 20 ORDER BY visits DESC LIMIT ? OFFSET ?");
    } else {
        echo json_encode([]);
        exit;
    }

    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch books and return them as JSON
    $books = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($books);
    exit;
}

// Check if a specific book's details page is requested (for visit tracking)
if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);

    // Increment the visits count for the book
    $title = "UPDATE BOOKS SET visits = visits + 1 WHERE book_id = $book_id";
    $database->query($title);

    // Fetch the book details
    $title = "SELECT * FROM BOOKS WHERE book_id = $book_id";
    $result = $database->query($title);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        // Display book details
        echo "<h1>" . htmlspecialchars($book['TITLE']) . "</h1>";
        echo "<p>by " . htmlspecialchars($book['AUTHOR']) . "</p>";
        echo "<img src='db/Book_Covers/" . htmlspecialchars($book['BOOK COVER']) . "' alt='" . htmlspecialchars($book['TITLE']) . " book cover'>";
        echo "<p>Visits: " . $book['visits'] . "</p>";
        exit;
    } else {
        echo "Book not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library</title>
    <link rel="icon" type="image/x-icon" href="E-Library/img/favicon/sk.ico">
    <link rel="stylesheet" href="E-Library/css/styles1.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="E-Library/css/header.css?v=<?php time(); ?>">
    <link rel="stylesheet" href="E-Library/css/footer.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php
    include 'E-Library/header.php';
    ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1><i class="fas fa-book-open"></i> Welcome to Our Digital Library</h1>
            <p>Discover thousands of books across various genres, from classics to contemporary bestsellers.</p>
        </div>
    </section>

    <!-- Search Container -->
    <div class="container">
        <div class="search-container">
            <h2 class="search-title"><i class="fas fa-search"></i> Find Your Next Read</h2>
            <div class="search-bar">
                <form id="form">
                    <div class="sb">
                        <input type="text" name="title" id="searchInput" placeholder="Search for books, authors, or genres...">
                        <input type="text" name="author" placeholder="Author name">
                        <select name="genre" class="select-option">
                            <option value="">All Categories</option>
                            <?php
                            $cat = $database->prepare("SELECT DISTINCT CATEGORY FROM books");
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
                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="submit-btn"><i class="fas fa-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results Section -->
        <h3 id="h3">Search Results</h3>
        <div class="search-results" id="rs">
            <div class="book-list" id="results"></div>
        </div>

        <!-- Featured Books Section -->
        <div class="section" id="fb">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-star"></i> Featured Books</h2>
            </div>
            <div class="book-list" id="featured-books">
                <?php
                $title = 'SELECT book_id, TITLE, AUTHOR, BOOK_COVER AS IMAGE FROM BOOKS ORDER BY book_id ASC LIMIT 10';
                $result = $database->query($title);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="book-item fadeIn">';
                        echo '<div class="book-coverpage">';
                        echo '<a href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '"><img src="E-Library/img/Book_Covers/' . htmlspecialchars($row['IMAGE']) . '" alt="' . htmlspecialchars($row['TITLE']) . ' book cover image"></a>';
                        echo '</div>';
                        echo '<div class="book-title">';
                        echo '<a class="title-anchor" href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '">' . htmlspecialchars($row['TITLE']) . '</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No books found</p>';
                }
                ?>
            </div>
            <button class="show-more-btn" onclick="loadMoreBooks('featured')"><i class="fas fa-plus"></i> Show More</button>
        </div>

        <!-- Most Visited Books Section -->
        <div class="section" id="msv">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-chart-line"></i> Most Popular Books</h2>
            </div>
            <div class="book-list" id="most-visited-books">
                <?php
                $title = 'SELECT book_id, TITLE, AUTHOR, `BOOK_COVER` AS IMAGE FROM BOOKS WHERE visits > 0 ORDER BY visits DESC LIMIT 10';
                $result = $database->query($title);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="book-item fadeIn">';
                        echo '<div class="book-coverpage">';
                        echo '<a href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '"><img src="E-Library/img/Book_Covers/' . htmlspecialchars($row['IMAGE']) . '" alt="' . htmlspecialchars($row['TITLE']) . ' book cover image"></a>';
                        echo '</div>';
                        echo '<div class="book-title">';
                        echo '<a class="title-anchor" href="E-Library/bookinfo.php?book_id=' . $row['book_id'] . '">' . htmlspecialchars($row['TITLE']) . '</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No books found</p>';
                }
                ?>
            </div>
            <button class="show-more-btn" onclick="loadMoreBooks('most-visited')"><i class="fas fa-plus"></i> Show More</button>
        </div>

        <!-- Star Rating Section -->
        <div class="star-rating-section" id="star-rating">
            <h2><i class="fas fa-thumbs-up"></i> Your Feedback Matters</h2>
            <p>Help us improve our library service by sharing your experience</p>
            <div class="star-rating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <div id="rating-message"></div>

            <!-- Review Section -->
            <textarea id="user-review" rows="4" placeholder="Share your thoughts about our library..."></textarea>
            <button id="submit-review" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit Feedback</button>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'E-Library/footer.php'; ?>

    <script src="E-Library/Script_Functions.js"></script>

    <script>
        // Search Functionality
        document.getElementById('form').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            document.getElementById('results').innerHTML = '<div class="loading-indicator"><i class="fas fa-spinner fa-spin"></i><span>Searching...</span></div>';
            
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch('E-Library/searchResult.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                });
                
                document.getElementById('results').innerHTML = '';
                
                const data = await response.json();
                console.log(data);
                
                // Hide the other sections and display only the results section
                document.getElementById('fb').style.display = 'none';
                document.getElementById('msv').style.display = 'none';
                document.getElementById('rs').style.display = 'block';
                document.getElementById('h3').style.display = 'block';
                
                if (data.message) {
                    document.getElementById('results').innerHTML = `<p class="no-results">${data.message}</p>`;
                } else {
                    data.forEach((book, index) => {
                        const bookDiv = document.createElement('div');
                        bookDiv.classList.add('book-item', 'fadeIn');
                        bookDiv.style.animationDelay = `${index * 0.1}s`;
                        
                        const coverPage = document.createElement('div');
                        coverPage.classList.add('book-coverpage');
                        
                        const coverLink = document.createElement('a');
                        coverLink.href = 'E-Library/bookinfo.php?book_id=' + book.book_id;
                        
                        const coverImage = document.createElement('img');
                        coverImage.src = 'E-Library/img/Book_Covers/' + book.IMAGE;
                        coverImage.alt = book.TITLE + ' book cover image';
                        
                        coverLink.appendChild(coverImage);
                        coverPage.appendChild(coverLink);
                        bookDiv.appendChild(coverPage);
                        
                        const titleDiv = document.createElement('div');
                        titleDiv.classList.add('book-title');
                        
                        const titleLink = document.createElement('a');
                        titleLink.href = 'E-Library/bookinfo.php?book_id=' + book.book_id;
                        titleLink.classList.add('title-anchor');
                        titleLink.textContent = book.TITLE;
                        
                        titleDiv.appendChild(titleLink);
                        bookDiv.appendChild(titleDiv);
                        
                        document.getElementById('results').appendChild(bookDiv);
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('results').innerHTML = '<p class="error">An error occurred while searching. Please try again.</p>';
            }
        });
        
        // Load More Books Function
        function loadMoreBooks(section) {
            const booksContainer = document.getElementById(section === 'featured' ? 'featured-books' : 'most-visited-books');
            const currentCount = booksContainer.getElementsByClassName('book-item').length;
            const loadMoreBtn = booksContainer.nextElementSibling;
            
            // Show loading indicator
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'loading-indicator';
            loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading more books...</span>';
            booksContainer.after(loadingIndicator);
            loadMoreBtn.style.display = 'none';
            
            fetch(`index.php?section=${section}&limit=10&offset=${currentCount}`)
                .then(response => response.json())
                .then(data => {
                    // Remove loading indicator
                    loadingIndicator.remove();
                    loadMoreBtn.style.display = 'block';
                    
                    if (data.length > 0) {
                        data.forEach((book, index) => {
                            const bookDiv = document.createElement('div');
                            bookDiv.className = 'book-item fadeIn';
                            bookDiv.style.animationDelay = `${index * 0.1}s`;
                            
                            bookDiv.innerHTML = `
                                <div class="book-coverpage">
                                    <a href="E-Library/bookinfo.php?book_id=${book.book_id}">
                                        <img src="E-Library/img/Book_Covers/${book.IMAGE}" alt="${book.TITLE} book cover image">
                                    </a>
                                </div>
                                <div class="book-title">
                                    <a class="title-anchor" href="E-Library/bookinfo.php?book_id=${book.book_id}">${book.TITLE}</a>
                                </div>
                            `;
                            
                            booksContainer.appendChild(bookDiv);
                        });
                        
                        // If fewer than 10 books returned, hide the "Show More" button
                        if (data.length < 10) {
                            loadMoreBtn.style.display = 'none';
                        }
                    } else {
                        loadMoreBtn.textContent = 'No more books to load';
                        loadMoreBtn.disabled = true;
                        setTimeout(() => {
                            loadMoreBtn.style.display = 'none';
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingIndicator.innerHTML = '<p>Error loading more books. Please try again.</p>';
                    loadMoreBtn.style.display = 'block';
                });
        }
        
        // Star Rating Functionality
        const stars = document.querySelectorAll('.star');
        let selectedRating = 0;
        
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                const ratingValue = parseInt(star.getAttribute('data-value'));
                
                stars.forEach((s, index) => {
                    if (index < ratingValue) {
                        s.classList.add('hover');
                    } else {
                        s.classList.remove('hover');
                    }
                });
            });
            
            star.addEventListener('mouseout', () => {
                stars.forEach(s => {
                    s.classList.remove('hover');
                });
            });
            
            star.addEventListener('click', () => {
                selectedRating = parseInt(star.getAttribute('data-value'));
                
                stars.forEach((s, index) => {
                    if (index < selectedRating) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
                
                document.getElementById('rating-message').textContent = `Thank you! You've rated our library ${selectedRating} ${selectedRating === 1 ? 'star' : 'stars'}.`;
            });
        });
        
        // Submit Review Button
        document.getElementById('submit-review').addEventListener('click', () => {
            const reviewText = document.getElementById('user-review').value.trim();
            const ratingMessage = document.getElementById('rating-message');
            
            if (selectedRating === 0) {
                ratingMessage.textContent = 'Please select a rating before submitting.';
                ratingMessage.style.color = '#e53e3e';  // Red color for error
                return;
            }
            
            // Here you would typically send the rating and review to the server
            // For now, just show a thank you message
            ratingMessage.textContent = reviewText 
                ? `Thank you for your ${selectedRating}-star rating and feedback! We appreciate your input.` 
                : `Thank you for your ${selectedRating}-star rating!`;
            ratingMessage.style.color = '#2b6cb0';  // Primary color for success
            
            document.getElementById('user-review').value = '';
            
            // Show a success message that fades away
            setTimeout(() => {
                const successMessage = document.createElement('div');
                successMessage.className = 'fadeIn';
                successMessage.style.marginTop = '10px';
                successMessage.style.padding = '10px';
                successMessage.style.backgroundColor = '#c6f6d5';
                successMessage.style.color = '#2f855a';
                successMessage.style.borderRadius = '4px';
                successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Your feedback has been submitted successfully!';
                
                ratingMessage.parentNode.insertBefore(successMessage, ratingMessage.nextSibling);
                
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    successMessage.style.transition = 'opacity 1s ease';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 1000);
                }, 3000);
            }, 500);
        });
        
        // Initialize - Hide search results section on page load
        window.onload = function() {
            document.getElementById('rs').style.display = 'none';
            document.getElementById('h3').style.display = 'none';
            
        // Add subtle reveal animations for the book sections
        const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fadeIn');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            });
            
            // Observe all sections for animation
            document.querySelectorAll('.section').forEach(section => {
                observer.observe(section);
            });
            
            // Add book hover effect for better interactivity
            const bookItems = document.querySelectorAll('.book-item');
            bookItems.forEach(item => {
                item.addEventListener('mouseenter', () => {
                    const image = item.querySelector('img');
                    if (image) {
                        image.style.transform = 'scale(1.05)';
                    }
                });
                
                item.addEventListener('mouseleave', () => {
                    const image = item.querySelector('img');
                    if (image) {
                        image.style.transform = 'scale(1)';
                    }
                });
            });
            
            // Add smooth scrolling for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Enable search suggestions
            const searchInput = document.getElementById('searchInput');
            const suggestionsContainer = document.createElement('div');
            suggestionsContainer.className = 'search-suggestions';
            suggestionsContainer.style.display = 'none';
            suggestionsContainer.style.position = 'absolute';
            suggestionsContainer.style.zIndex = '10';
            suggestionsContainer.style.backgroundColor = 'white';
            suggestionsContainer.style.width = 'calc(100% - 2px)';
            suggestionsContainer.style.borderRadius = '0 0 var(--border-radius) var(--border-radius)';
            suggestionsContainer.style.border = '1px solid var(--border-color)';
            suggestionsContainer.style.borderTop = 'none';
            suggestionsContainer.style.maxHeight = '200px';
            suggestionsContainer.style.overflowY = 'auto';
            suggestionsContainer.style.boxShadow = 'var(--shadow)';
            
            searchInput.parentNode.style.position = 'relative';
            searchInput.parentNode.appendChild(suggestionsContainer);
            
            let debounceTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    const query = searchInput.value.trim();
                    if (query.length >= 3) {
                        // In a real implementation, this would fetch suggestions from the server
                        // For demo purposes, we'll just show some placeholder suggestions
                        const suggestions = [
                            `Search for "${query}"`,
                            `Books related to "${query}"`,
                            `Authors related to "${query}"`
                        ];
                        
                        suggestionsContainer.innerHTML = '';
                        suggestions.forEach(suggestion => {
                            const div = document.createElement('div');
                            div.textContent = suggestion;
                            div.style.padding = '10px 15px';
                            div.style.cursor = 'pointer';
                            div.style.transition = 'background-color 0.2s ease';
                            
                            div.addEventListener('mouseenter', () => {
                                div.style.backgroundColor = '#f7fafc';
                            });
                            
                            div.addEventListener('mouseleave', () => {
                                div.style.backgroundColor = 'transparent';
                            });
                            
                            div.addEventListener('click', () => {
                                searchInput.value = suggestion.replace('Search for "', '').replace('Books related to "', '').replace('Authors related to "', '').replace('"', '');
                                suggestionsContainer.style.display = 'none';
                            });
                            
                            suggestionsContainer.appendChild(div);
                        });
                        
                        suggestionsContainer.style.display = 'block';
                    } else {
                        suggestionsContainer.style.display = 'none';
                    }
                }, 300);
            });
            
            // Hide suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (e.target !== searchInput && e.target !== suggestionsContainer) {
                    suggestionsContainer.style.display = 'none';
                }
            });
            
            // Add dark mode toggle
            const createDarkModeToggle = () => {
                const toggle = document.createElement('button');
                toggle.className = 'dark-mode-toggle';
                toggle.innerHTML = '<i class="fas fa-moon"></i>';
                toggle.style.position = 'fixed';
                toggle.style.bottom = '20px';
                toggle.style.right = '20px';
                toggle.style.zIndex = '1000';
                toggle.style.width = '50px';
                toggle.style.height = '50px';
                toggle.style.borderRadius = '50%';
                toggle.style.backgroundColor = 'var(--primary-color)';
                toggle.style.color = 'white';
                toggle.style.border = 'none';
                toggle.style.cursor = 'pointer';
                toggle.style.boxShadow = 'var(--shadow)';
                toggle.style.transition = 'var(--transition)';
                
                let isDarkMode = localStorage.getItem('darkMode') === 'true';
                
                const updateDarkMode = () => {
                    if (isDarkMode) {
                        document.documentElement.style.setProperty('--background-color', '#1a202c');
                        document.documentElement.style.setProperty('--card-background', '#2d3748');
                        document.documentElement.style.setProperty('--text-color', '#e2e8f0');
                        document.documentElement.style.setProperty('--text-light', '#a0aec0');
                        document.documentElement.style.setProperty('--border-color', '#4a5568');
                        toggle.innerHTML = '<i class="fas fa-sun"></i>';
                    } else {
                        document.documentElement.style.setProperty('--background-color', '#f7fafc');
                        document.documentElement.style.setProperty('--card-background', '#ffffff');
                        document.documentElement.style.setProperty('--text-color', '#2d3748');
                        document.documentElement.style.setProperty('--text-light', '#718096');
                        document.documentElement.style.setProperty('--border-color', '#e2e8f0');
                        toggle.innerHTML = '<i class="fas fa-moon"></i>';
                    }
                };
                
                updateDarkMode();
                
                toggle.addEventListener('click', () => {
                    isDarkMode = !isDarkMode;
                    localStorage.setItem('darkMode', isDarkMode);
                    updateDarkMode();
                });
                
                document.body.appendChild(toggle);
            };
            
            createDarkModeToggle();
            
            // Add back-to-top button
            const createBackToTopButton = () => {
                const button = document.createElement('button');
                button.className = 'back-to-top';
                button.innerHTML = '<i class="fas fa-arrow-up"></i>';
                button.style.position = 'fixed';
                button.style.bottom = '80px';
                button.style.right = '20px';
                button.style.zIndex = '1000';
                button.style.width = '50px';
                button.style.height = '50px';
                button.style.borderRadius = '50%';
                button.style.backgroundColor = 'var(--primary-color)';
                button.style.color = 'white';
                button.style.border = 'none';
                button.style.cursor = 'pointer';
                button.style.boxShadow = 'var(--shadow)';
                button.style.transition = 'var(--transition)';
                button.style.opacity = '0';
                button.style.pointerEvents = 'none';
                
                button.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
                
                document.body.appendChild(button);
                
                window.addEventListener('scroll', () => {
                    if (window.pageYOffset > 300) {
                        button.style.opacity = '1';
                        button.style.pointerEvents = 'auto';
                    } else {
                        button.style.opacity = '0';
                        button.style.pointerEvents = 'none';
                    }
                });
            };
            
            createBackToTopButton();
            
            // Add book preview functionality
            document.querySelectorAll('.book-coverpage a').forEach(link => {
                link.addEventListener('click', function(e) {
                    // This would typically open a modal with book preview
                    // For demo purposes, we're just preventing default behavior
                    // and showing how it would work
                    /*
                    e.preventDefault();
                    const bookId = this.href.split('book_id=')[1];
                    
                    // Create and show preview modal
                    const modal = document.createElement('div');
                    modal.className = 'book-preview-modal';
                    modal.innerHTML = `
                        <div class="book-preview-content">
                            <button class="close-modal"><i class="fas fa-times"></i></button>
                            <h3>Loading preview...</h3>
                            <div class="book-preview-loading">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    
                    // In a real implementation, fetch book details here
                    */
                });
            });
        };
    </script>
</body>
</html>