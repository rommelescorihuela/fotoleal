<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Enlace]].
 *
 * @see Enlace
 */
class EnlaceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Enlace[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Enlace|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
