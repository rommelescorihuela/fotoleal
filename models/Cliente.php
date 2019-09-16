<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property string $id_cliente
 * @property string $cedula
 * @property string $nombre
 * @property string $apellido
 * @property string $correo
 * @property string $telefono
 * @property int $celular
 * @property int $asistencia
 * @property int $id_direccion
 * @property string $id_programa
 *
 * @property Programa $programa
 * @property Enlace[] $enlaces
 * @property Pago[] $pagos
 */
class Cliente extends \yii\db\ActiveRecord
{
      public $carga;
      public $evento;
      public $programa;
      public $tipo_pago;
      public $observaciones;
      public $monto;
      public $todo;
      public $paquete1;
      public $paquete2;
      public $tipo_carrera;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cedula', 'telefono', 'celular', 'id_direccion', 'id_programa'], 'integer'],
            [['id_direccion', 'id_programa'], 'safe'],
            [['cedula', 'telefono', 'celular','correo','observaciones','tipo_carrera'], 'required','on'=> 'principal'],
            [['observaciones', 'monto','asistencia'], 'safe'],
            [['nombre'], 'string', 'max' => 255],
            [['apellido'], 'string', 'max' => 255],
            [['correo'], 'string', 'max' => 255],
            [['correo'], 'email'],
            [['id_programa'], 'exist', 'skipOnError' => true, 'targetClass' => Programa::className(), 'targetAttribute' => ['id_programa' => 'id_programa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cliente' => 'Id Cliente',
            'cedula' => 'Cedula',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'correo' => 'Correo',
            'telefono' => 'Telefono',
            'celular' => 'Celular',
            'id_direccion' => 'Id Direccion',
            'id_programa' => 'Programa',
            'carga' => 'Seleccionar archivo a cargar',
            'observaciones'=>'Tipo de pago',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma()
    {
        return $this->hasOne(Programa::className(), ['id_programa' => 'id_programa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnlaces()
    {
        return $this->hasMany(Enlace::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagos()
    {
        return $this->hasMany(Pago::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * {@inheritdoc}
     * @return ClienteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ClienteQuery(get_called_class());
    }
}
