<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Topheader -->
<div class="top-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="logo">
                    <a href="..">
                        <img src="../../web/img/logoticketgo.png" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="col-md-6">

            </div>
            <div class="col-md-2 text-right">
                <div class="user-icons d-flex justify-content-end">
                    <div class="perfil">
                        <a href="/login">
                            <img src="../../web/img/icon_perfil.png" alt="Perfil" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="favoritos">
                        <a href="/favorites">
                            <img src="../../web/img/icon_coracao.png" alt="Favoritos" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="carrinho">
                        <a href="/cart">
                            <img src="../../web/img/icon_carrinho.png" alt="Carrinho" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topheader -->

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