<?php

namespace common\models;

use common\models\LinhaFatura;
use common\models\MetodoPagamentoagamento;
use common\models\Profile;



use Yii;

/**
 * This is the model class for table "Faturas".
 *
 * @property int $id
 * @property string|null $numerofatura
 * @property int|null $metodopagamento_id
 * @property int|null $profile_id
 * @property float|null $valortotal
 * @property string|null $dataemissao
 *
 * @property LinhasFatura[] $linhasFaturas
 * @property MetodosPagamento $metodopagamento
 * @property Profiles $profile
 */
class Fatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metodopagamento_id', 'profile_id'], 'integer'],
            [['valortotal'], 'number'],
            [['dataemissao'], 'safe'],
            [['numerofatura'], 'string', 'max' => 50],
            [['numerofatura'], 'unique'],
            [['metodopagamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetodoPagamento::class, 'targetAttribute' => ['metodopagamento_id' => 'id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numerofatura' => 'Numerofatura',
            'metodopagamento_id' => 'Metodopagamento ID',
            'profile_id' => 'Profile ID',
            'valortotal' => 'Valortotal',
            'dataemissao' => 'Dataemissao',
        ];
    }

    /**
     * Gets query for [[LinhasFaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasFaturas()
    {
        return $this->hasMany(LinhaFatura::class, ['fatura_id' => 'id']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodopagamento()
    {
        return $this->hasOne(MetodoPagamento::class, ['id' => 'metodopagamento_id']);
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
