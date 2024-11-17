<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketGo - Bem-vindo!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">
                <img src="logo.svg" alt="Logo TicketGo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sobre Nós</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contato</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder=" Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                </form>
            </div>
        </nav>
    </div>
</header>

<main>
    <div class="container mt-4">
        <h1>Bem-vindo ao TicketGo!</h1>
        <p>Encontre os melhores eventos e compre seus ingressos de forma fácil e rápida.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="event1.jpg" class="card-img-top" alt="Evento 1">
                    <div class="card-body">
                        <h5 class="card-title">Evento 1</h5>
                        <p class="card-text">Descrição do evento 1.</p>
                        <a href="#" class="btn btn-primary">Comprar Ingresso</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="event2.jpg" class="card-img-top" alt="Evento 2">
                    <div class="card-body">
                        <h5 class="card-title">Evento 2</h5>
                        <p class="card-text">Descrição do evento 2.</p>
                        <a href="#" class="btn btn-primary">Comprar Ingresso</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="event3.jpg" class="card-img-top" alt="Evento 3">
                    <div class="card-body">
                        <h5 class="card-title">Evento 3</h5>
                        <p class="card-text">Descrição do evento 3.</p>
                        <a href="#" class="btn btn-primary">Comprar Ingresso</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="footer mt-4">
    <div class="container">
        <p class="text-center">&copy; 2023 TicketGo. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>