<?php

// Publica uma mensagem no tópico "cart/events"
exec("mosquitto_pub -t 'cart/events' -m 'Carrinho finalizado pelo usuário 123'");
echo "Mensagem publicada com sucesso!";
