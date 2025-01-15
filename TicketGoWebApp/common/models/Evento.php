<?php


namespace common\models;

use common\models\Bilhete;
use common\models\Categoria;
use common\models\Favorito;
use common\models\Imagem;
use common\models\Local;

use Yii;

/**
 * This is the model class for table "Eventos".
 *
 * @property int $id
 * @property string|null $titulo
 * @property string|null $descricao
 * @property string|null $datainicio
 * @property string|null $datafim
 * @property int|null $local_id
 * @property int|null $categoria_id
 *
 * @property Bilhetes[] $bilhetes
 * @property Categorias $categoria
 * @property Favoritos[] $favoritos
 * @property Imagens $imagens
 * @property Locais $local
 */
class Evento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'string'],
            [['datainicio', 'datafim'], 'safe'],
            [['local_id', 'categoria_id'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
            [['local_id'], 'exist', 'skipOnError' => true, 'targetClass' => Local::class, 'targetAttribute' => ['local_id' => 'id']],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            //[['imagem'], 'file', 'extensions' => 'jpg, png, jpeg', 'maxSize' => 1024 * 1024 * 2], // Máx: 2 MB

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'datainicio' => 'Datainicio',
            'datafim' => 'Datafim',
            'local_id' => 'Local ID',
            'categoria_id' => 'Categoria ID',
            //'imagem' => 'Imagem',

        ];
    }

    /**
     * Gets query for [[Bilhetes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBilhetes()
    {
        return $this->hasMany(Bilhete::class, ['evento_id' => 'id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favorito::class, ['evento_id' => 'id']);
    }

    /**
     * Gets query for [[Imagens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasOne(Imagem::class, ['evento_id' => 'id']);
    }

    /**
     * Gets query for [[Local]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocal()
    {
        return $this->hasOne(Local::class, ['id' => 'local_id']);
    }

}
