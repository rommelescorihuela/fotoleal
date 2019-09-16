<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Rol]].
 *
 * @see Rol
 */
class RolQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rol[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rol|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
