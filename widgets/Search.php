<?php

namespace domain\modules\text\widgets;


use domain\modules\text\forms\SearchForm;
use yii\base\Widget;

class Search extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $searchModel = new SearchForm();

        return $this->render('search', [
            'searchModel' => $searchModel,
        ]);
    }
}