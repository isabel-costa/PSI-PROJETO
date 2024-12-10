<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">TicketGo</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="me-auto"></div>
            <div class="user-icons d-flex align-items-center ms-3">
                <a href="favorites.php" class="nav-link">Favorites</a>
                <a href="site/login" class="nav-link">Login</a>
                <a href="site/cart" class="nav-link">Cart</a>
            </div>
        </div>
    </div>
</nav>

<!-- Favorites Section -->
<div class="container mt-5">
    <h2>Your Favorite Events</h2>
    <div id="favorites-list" class="row">
        <!-- Favorite items will be dynamically inserted here -->
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>