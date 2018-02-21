<?php

namespace abdualiym\text;


use Yii;

/**
 * Menu module definition class
 */
class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
//        $this->registerTranslations();
    }

//    public function registerTranslations()
//    {
//        Yii::$app->i18n->translations['modules/text/*'] = [
//            'class' => 'yii\i18n\PhpMessageSource',
//            'sourceLanguage' => 'en',
//            'basePath' => '@domain/modules/text/messages',
//            'fileMap' => [
//                'modules/text/text' => 'text.php',
//            ],
//        ];
//    }
//
//    public static function t($category, $message, $params = [], $language = null)
//    {
//        return Yii::t('modules/text/' . $category, $message, $params, $language);
//    }
}
