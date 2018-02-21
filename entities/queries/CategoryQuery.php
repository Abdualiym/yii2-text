<?php

namespace domain\modules\text\entities\queries;

use domain\modules\text\entities\Category;
use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Category::STATUS_ACTIVE,
        ]);
    }
}