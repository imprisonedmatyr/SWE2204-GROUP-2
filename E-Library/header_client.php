<header class="header">
	<a href="index.php" class="logo">Library</a>
	<div class="user-icon" onclick="toggleDropdown()">
		<div class="user-circle">
			<?php
			// Display the first letter of the username if set
			if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] != 1) {
				echo strtoupper(substr($_SESSION['username'], 0, 1));
			} else {
				echo '<img src="E-Library/img/fontawesome-free-6.6.0-web/svgs/regular/account-svgrepo-com.svg" style="width: 2.92rem; height: auto;">';
			}
			?>
		</div>
		<div class="dropdown">
			<?php if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] != 1): ?>
				<a href="index.php" class="logo">Home</a>
				<a href="E-Library/search.php">Browse</a>
				<a href="E-Library/userdashboard.php">Dashboard</a>
				<a href="E-Library/Accountinfo.php">Profile</a>
				<a href="E-Library/favourites.php">My Library</a>
				<a href="E-Library/logout.php">Logout</a>
			<?php else: ?>
				<a href="index.php" class="logo">Home</a>
				<a href="E-Library/signIn.php">log In</a>
				<a href="E-Library/CreateAccount.php">Sign Up</a>
			<?php endif; ?>
		</div>
	</div>
</header>

<script>
	function toggleDropdown() {
		document.querySelector('.user-icon').classList.toggle('active');
	}

	// Close the dropdown if clicked outside
	window.addEventListener('click', function(e) {
		if (!document.querySelector('.user-icon').contains(e.target)) {
			document.querySelector('.user-icon').classList.remove('active');
		}
	});
</script>
