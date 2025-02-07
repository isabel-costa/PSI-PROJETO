<?php

namespace common\models;

use Bluerhinos\phpMQTT;

require_once __DIR__ . '/../libs/phpMQTT.php';


class mqttPublisher extends \yii\db\ActiveRecord
{

    public static function publish($topico, $mensagem, $qos = 0)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "phpMQTT_publisher";

        $mqtt = new phpMQTT($server, $port, $client_id);

        if ($mqtt->connect(true, NULL, $username, $password)) {

            $mqtt->publish($topico, $mensagem, $qos);
            $mqtt->close();
        }

    }


}
