<?php
$evento = $bilhete->evento->titulo ?? 'Evento desconhecido';
$codigo = $bilhete->codigobilhete;
$zona = $bilhete->zona->lugar ?? 'Zona desconhecida';
$data = date('d/m/Y', strtotime($bilhete->data));
$preco = number_format($bilhete->precounitario, 2, ',', '.');

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilhete - <?= htmlspecialchars($evento) ?></title>
    <link href="css/bilhete.css" rel="stylesheet">
</head>
<body>

<div class="ticket-container">
    <div class="ticket-header">
        Bilhete para <?= htmlspecialchars($evento) ?>
    </div>

    <div class="ticket-content">
        <p><strong>Evento:</strong> <?= htmlspecialchars($evento) ?></p>
        <p><strong>Data:</strong> <?= htmlspecialchars($data) ?></p>
        <p><strong>Zona:</strong> <?= htmlspecialchars($zona) ?></p>
        <p><strong>Preço:</strong> €<?= htmlspecialchars($preco) ?></p>

        <div class="ticket-code">
            <?= htmlspecialchars($codigo) ?>
        </div>

        <div class="qr-code">
            <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?= urlencode($codigo) ?>&choe=UTF-8" alt="QR Code">
        </div>
    </div>

    <div class="ticket-footer">
        Apresente este bilhete na entrada do evento.
    </div>
</div>

</body>
</html>

