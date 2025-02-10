<?php

namespace common\models;

use common\models\Evento;
use common\models\Zona;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "Local".
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $morada
 * @property string|null $cidade
 * @property int|null $capacidade
 *
 * @property Eventos[] $eventos
 * @property Zonas[] $zonas
 */
class Local extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'locais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['capacidade'], 'integer'],
            [['nome', 'cidade'], 'string', 'max' => 100],
            [['morada'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'local_id' => 'ID',
            'nome' => 'Nome',
            'morada' => 'Morada',
            'cidade' => 'Cidade',
            'capacidade' => 'Capacidade',
        ];
    }

    /**
     * Gets query for [[Eventos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Evento::class, ['local_id' => 'id']);
    }

    /**
     * Gets query for [[Zonas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZonas()
    {
        return $this->hasMany(Zona::class, ['local_id' => 'id']);
    }
}