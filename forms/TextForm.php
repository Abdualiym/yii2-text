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
 * @property PhotosForm $photos
 */
class TextForm extends CompositeForm
{
    public $category_id;
    public $date;
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
            $this->photos = new PhotosForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['date'], 'required'],
            [['category_id'], 'integer'],
            [['date'], 'string', 'max' => 12],
        ];
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(
            Category::find()->where(['status' => Category::STATUS_ACTIVE])->with('translations')->asArray()->all(), 'id', function (array $category) {
            return $category['translations']['name'];
        });
    }

    public function textsList(): array
    {
        return ArrayHelper::map(
            Text::find()->where(['status' => Text::STATUS_ACTIVE])->with('translations')->asArray()->all(), 'id', function (array $text) {
            return $text['translations']['title'];
        });
    }

    public function internalForms(): array
    {
        return ['translations', 'photos'];
    }
}