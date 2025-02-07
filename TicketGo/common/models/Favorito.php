<?php


namespace common\models;

use common\models\Evento;
use common\models\Profile;

use Yii;

/**
 * This is the model class for table "Favoritos".
 *
 * @property int $id
 * @property int|null $profile_id
 * @property int|null $evento_id
 *
 * @property Eventos $evento
 * @property Profiles $profile
 */
class Favorito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Favoritos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'evento_id'], 'integer'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'id']],
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
            'profile_id' => 'Profile ID',
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

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['id' => 'profile_id']);
    }
}
