<?php

namespace abdualiym\text;

use yii\base\BootstrapInterface;

/**
 * Contacts module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module URL rules.
//        Yii::$app->urlManager->addRules(
//            [
//                'captcha'=>'/site/captcha',
//                'feedback' => 'contacts/index',
//            ],
//            false
//        );

        // Add module I18N category.
        if (!isset($app->i18n->translations['text']) && !isset($app->i18n->translations['text*'])) {
            $app->i18n->translations['text'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@abdualiym/contacts/messages',
//                'basePath' => '@vendor/abdualiym/yii2-text/messages',
                'sourceLanguage' => 'en',
                'forceTranslation' => true,
            ];
        }
    }
}
