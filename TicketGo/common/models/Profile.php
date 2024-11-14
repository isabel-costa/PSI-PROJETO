<?php

namespace common\models;

use app\models\Carrinhos;
use app\models\Faturas;
use app\models\Favoritos;
use Yii;

/**
 * This is the model class for table "Profiles".
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $datanascimento
 * @property int|null $nif
 * @property string|null $morada
 * @property string|null $dataregisto
 *
 * @property Carrinhos $carrinhos
 * @property Faturas[] $faturas
 * @property Favoritos[] $favoritos
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datanascimento', 'dataregisto'], 'safe'],
            [['nif'], 'integer'],
            [['nome'], 'string', 'max' => 50],
            [['morada'], 'string', 'max' => 200],
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
            'datanascimento' => 'Datanascimento',
            'nif' => 'Nif',
            'morada' => 'Morada',
            'dataregisto' => 'Dataregisto',
        ];
    }

    /**
     * Gets query for [[Carrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhos()
    {
        return $this->hasOne(Carrinhos::class, ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favoritos::class, ['profile_id' => 'id']);
    }
}
