<?php


namespace common\models;

use common\models\Evento;

use Yii;

/**
 * This is the model class for table "Imagens".
 *
 * @property int $id
 * @property string|null $nome
 * @property int|null $evento_id
 *
 * @property Eventos $evento
 */
class Imagem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Imagens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evento_id'], 'integer'],
            [['nome'], 'string', 'max' => 50],
            [['evento_id'], 'unique'],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Evento::class, 'targetAttribute' => ['evento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'evento_id' => 'Evento ID',
        ];
    }

    /**
     * Gets query for [[Evento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvento()
    {
        return $this->hasOne(Evento::class, ['id' => 'evento_id']);
    }

}
