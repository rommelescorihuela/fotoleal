<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "direccion".
 *
 * @property int $id_direccion
 * @property string $direccion
 * @property string $municipio
 * @property string $barrio
 * @property string $carrera1
 * @property string $carrera2
 * @property string $carrera3
 * @property string $casa1
 * @property string $casa2
 * @property string $tipo_carrera
 * @property string $tipo_casa
 *
 * @property Evento[] $eventos
 */
class Direccion extends \yii\db\ActiveRecord
{
  public $carrera;
  public $casa;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['barrio', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/i','message'=>Yii::t('app','no se aceptan carecteres especiales')],
            ['carrera1', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/i','message'=>Yii::t('app','introduzca solo numeros por favor')],
            ['carrera2', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/i','message'=>Yii::t('app','introduzca solo numeros por favor')],
            ['carrera3', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/i','message'=>Yii::t('app','introduzca solo numeros por favor')],
            ['casa1', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/i','message'=>Yii::t('app','introduzca solo numeros por favor')],
            ['casa2', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/i','message'=>Yii::t('app','introduzca solo numeros por favor')],
            [['direccion'], 'string', 'max' => 2048],
            [['municipio', 'barrio', 'carrera1', 'carrera2', 'carrera3', 'casa1', 'casa2', 'tipo_carrera', 'tipo_casa'], 'string', 'max' => 255],
            [['carrera'], 'safe'],
             [['carrera','casa','municipio', 'barrio', 'carrera1', 'carrera2', 'carrera3', 'tipo_carrera', 'tipo_casa'], 'required','on' =>'principal'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_direccion' => 'Id Direccion',
            'direccion' => 'Direccion',
            'municipio' => 'Municipio',
            'barrio' => 'Barrio',
            'carrera1' => 'Carrera1',
            'carrera2' => 'Carrera2',
            'carrera3' => 'Carrera3',
            'casa1' => 'Casa1',
            'casa2' => 'Casa2',
            'tipo_carrera' => 'Tipo Carrera',
           'tipo_casa' => 'Tipo Casa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Evento::className(), ['id_direccion' => 'id_direccion']);
    }

    /**
     * {@inheritdoc}
     * @return DireccionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DireccionQuery(get_called_class());
    }
}
