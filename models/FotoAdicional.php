<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "foto_adicional".
 *
 * @property int $id_foto
 * @property int $cantidad_foto
 * @property int $monto
 * @property int $total
 * @property int $id_usuario
 * @property int $id_cliente
 *
 * @property Cliente $cliente
 * @property Usuario $usuario
 * @property PagoFoto[] $pagoFotos
 */
class FotoAdicional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'foto_adicional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cantidad_foto', 'monto', 'total', 'id_usuario', 'id_cliente'], 'required'],
            [['cantidad_foto', 'monto', 'total', 'id_usuario', 'id_cliente'], 'integer'],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_foto' => 'Id Foto',
            'cantidad_foto' => 'Cantidad Foto',
            'monto' => 'Monto',
            'total' => 'Total',
            'id_usuario' => 'Id Usuario',
            'id_cliente' => 'Id Cliente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagoFotos()
    {
        return $this->hasMany(PagoFoto::className(), ['id_foto_adicional' => 'id_foto']);
    }

    /**
     * {@inheritdoc}
     * @return FotoAdicionalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FotoAdicionalQuery(get_called_class());
    }
}
