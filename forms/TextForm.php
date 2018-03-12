<?php

namespace abdualiym\text\forms;

use abdualiym\languageClass\Language;
use abdualiym\text\entities\Category;
use abdualiym\text\entities\Text;
use elisdn\compositeForm\CompositeForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property TextTranslationForm $translations
 */
class TextForm extends CompositeForm
{
    public $category_id;
    public $date;
    public $photo;
    private $_text;

    public function __construct(Text $text = null, $config = [])
    {
        if ($text) {
            $this->category_id = $text->category_id;
            $this->date = $text->date;
            $this->translations = array_map(function (array $language) use ($text) {
                return new TextTranslationForm($text->getTranslation($language['id']));
            }, Language::langList(\Yii::$app->params['languages']));
            $this->_text = $text;
        } else {
            $this->translations = array_map(function () {
                return new TextTranslationForm();
            }, Language::langList(\Yii::$app->params['languages']));
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['date'], 'required'],
            [['category_id'], 'integer'],
            [['date'], 'string', 'max' => 12],
            [['photo'], 'image'],
        ];
    }

    public function categoriesList($allLanguages = null): array
    {
        return ArrayHelper::map(
            Category::find()->with('translations')->asArray()->all(), 'id', function (array $category) use ($allLanguages) {
            return $allLanguages
                ? $category['translations']
                : $category['translations'][0]['name'];
        });
    }

    public function textsList($allLanguages = null): array
    {
        return ArrayHelper::map(
            Text::find()->with('translations')->asArray()->all(), 'id', function (array $text) use ($allLanguages) {
            return $allLanguages
                ? $text['translations']
                : $text['translations'][0]['title'];
        });
    }

    public function internalForms(): array
    {
        return ['translations'];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }

}