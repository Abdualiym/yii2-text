<?php

namespace domain\modules\text\entities\queries;

use domain\modules\text\entities\Text;
use yii\db\ActiveQuery;

class TextQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Text::STATUS_ACTIVE,
        ]);
    }
}