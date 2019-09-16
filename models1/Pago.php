<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago".
 *
 * @property int $id_pago
 * @property int $numero_referencia
 * @property double $monto
 * @property int $id_cliente
 * @property string $observaciones
 * @property string $paquete
 * @property string $ref_payco
 * @property string $confirmacion
 * @property string $factura
 * @property string $ref_payco_corto
 * @property int $id_usuario
 * @property string $tipo_pago
 * @property string $fecha_pago
 *
 * @property Cliente $cliente
 * @property Usuario $usuario
 */
class Pago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_referencia', 'monto', 'id_cliente', 'observaciones', 'id_usuario', 'tipo_pago', 'fecha_pago'], 'required'],
            [['numero_referencia', 'id_cliente', 'id_usuario'], 'integer'],
            [['monto'], 'number'],
            [['paquete','ref_payco','confirmacion','factura','ref_payco_corto'], 'safe'],
            [['observaciones'], 'string', 'max' => 50],
            [['tipo_pago'], 'string', 'max' => 30],
            [['fecha_pago'], 'string', 'max' => 20],
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
            'id_pago' => 'Id Pago',
            'numero_referencia' => 'Numero Referencia',
            'monto' => 'Monto',
            'id_cliente' => 'Id Cliente',
            'observaciones' => 'Observaciones',
            'id_usuario' => 'Id Usuario',
            'tipo_pago' => 'Tipo Pago',
            'fecha_pago' => 'Fecha Pago',
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
     * {@inheritdoc}
     * @return PagoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PagoQuery(get_called_class());
    }
}
