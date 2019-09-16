<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PagoFoto]].
 *
 * @see PagoFoto
 */
class PagoFotoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PagoFoto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PagoFoto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
