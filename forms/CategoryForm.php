<?php

namespace abdualiym\text\forms;

use abdualiym\languageClass\Language;
use abdualiym\text\entities\Category;
use elisdn\compositeForm\CompositeForm;

/**
 * @property CategoryTranslationForm $translations
 */
class CategoryForm extends CompositeForm
{
    public $feed_with_image;
    private $_category;

    public function __construct(Category $category = null, $config = [])
    {
        if ($category) {
            $this->feed_with_image = $category->feed_with_image;
            $this->translations = array_map(function (array $language) use ($category) {
                return new CategoryTranslationForm($category->getTranslation($language['id']));
            }, Language::langList(\Yii::$app->params['languages']));
            $this->_category = $category;
        } else {
            $this->translations = array_map(function () {
                return new CategoryTranslationForm();
            }, Language::langList(\Yii::$app->params['languages']));
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['feed_with_image'], 'required'],
            [['feed_with_image'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'feed_with_image' => 'Выберите шаблон',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
            'created_by' => 'Добавил',
            'updated_by' => 'Обновил',
        ];
    }

    public function internalForms()
    {
        return ['translations'];
    }
}