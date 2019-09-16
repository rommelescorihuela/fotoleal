<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evento".
 *
 * @property int $id_evento
 * @property string $nombre_evento
 * @property string $fecha_evento
 * @property double $monto_evento
 * @property double $abono
 * @property double $saldo
 * @property int $cerrado
 * @property double $monto_evento2
 * @property double $abono2
 * @property double $saldo2
 * @property string $paquete
 * @property string $paquete2
 *
 * @property Programa[] $programas
 */
class Evento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_evento', 'fecha_evento', 'monto_evento', 'abono', 'saldo', 'cerrado', 'monto_evento2', 'abono2', 'saldo2', 'paquete', 'paquete2'], 'required'],
            [['fecha_evento'], 'safe'],
            [['monto_evento', 'abono', 'saldo', 'monto_evento2', 'abono2', 'saldo2'], 'number'],
            [['cerrado'], 'integer'],
            [['nombre_evento'], 'string', 'max' => 100],
            [['paquete', 'paquete2'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_evento' => 'Id Evento',
            'nombre_evento' => 'Nombre Evento',
            'fecha_evento' => 'Fecha Evento',
            'monto_evento' => 'Monto Evento',
            'abono' => 'Abono',
            'saldo' => 'Saldo',
            'cerrado' => 'Cerrado',
            'monto_evento2' => 'Monto Evento2',
            'abono2' => 'Abono2',
            'saldo2' => 'Saldo2',
            'paquete' => 'Paquete',
            'paquete2' => 'Paquete2',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramas()
    {
        return $this->hasMany(Programa::className(), ['id_evento' => 'id_evento']);
    }

    /**
     * {@inheritdoc}
     * @return EventoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventoQuery(get_called_class());
    }
}
