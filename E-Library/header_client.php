<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
</head>
<body>
<header class="header">
	<a href="../index.php" class="logo">Library</a>
	<div class="user-icon" onclick="toggleDropdown()">
		<div class="user-circle">
			<?php
			// Display the first letter of the username if set
			if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] != 1) {
				echo strtoupper(substr($_SESSION['username'], 0, 1));
			} else {
				echo '<img src="img/fontawesome-free-6.6.0-web/svgs/regular/account-svgrepo-com.svg" style="width: 2.92rem; height: auto;">';
			}
			?>
		</div>
		<div class="dropdown">
			<?php if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] != 1): ?>
				<a href="../index.php" class="logo">Home</a>
				<a href="search.php">Browse</a>
				<a href="userdashboard.php">Dashboard</a>
				<a href="Accountinfo.php">Profile</a>
				<a href="favourites.php">My Library</a>
				<a href="logout.php">Logout</a>
			<?php else: ?>
				<a href="../index.php" class="logo">Home</a>
				<a href="signIn.php">log In</a>
				<a href="CreateAccount.php">Sign Up</a>
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
	
</body>
</html>
