<?php

use abdualiym\languageClass\Language;
use domain\modules\text\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model domain\modules\text\forms\TextForm */
/* @var $text domain\modules\text\entities\Text */

$langList = Language::langList(Yii::$app->params['languages'], true);

foreach ($model->translations as $i => $translation) {
    if (!$translation->lang_id) {
        $q = 0;
        foreach ($langList as $k => $l) {
            if ($i == $q) {
                $translation->lang_id = $k;
            }
            $q++;
        }
    }
}
$thumb = isset($text->photo) ? $text->getThumbFileUrl('photo', 'thumb') : '';
?>

<div class="text-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-8">

            <div class="box box-default">
                <div class="box-body">
                    <?= $form->errorSummary($model) ?>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
//                        \yii\helpers\VarDumper::dump($model->translations, 10, true);
//                        die;
                        foreach ($model->translations as $i => $translation) {
                            ?>
                            <li role="presentation" <?= $i == 0 ? 'class="active"' : '' ?>>
                                <a href="#<?= $langList[$translation->lang_id]['prefix'] ?>"
                                   aria-controls="<?= $langList[$translation->lang_id]['prefix'] ?>" role="tab" data-toggle="tab">
                                    <?= '(' . $langList[$translation->lang_id]['prefix'] . ') ' . $langList[$translation->lang_id]['title'] ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <br>
                        <?php foreach ($model->translations as $i => $translation): ?>
                            <div role="tabpanel" class="tab-pane <?= $i == 0 ? 'active' : '' ?>"
                                 id="<?= $langList[$translation->lang_id]['prefix'] ?>">
                                <?= $form->field($translation, '[' . $i . ']title')->textInput(['maxlength' => true])->label("Название на (" . $langList[$translation->lang_id]['title'] . ")") ?>
                                <?php //= $form->field($model->translations, 'slug[' . $i . ']')->textInput(['maxlength' => true, 'value' => ($translation != '') ? $translation[$i]['slug'] : $translation]) ?>
                                <?= $form->field($translation, '[' . $i . ']description')->textarea() ?>
                                <?= $form->field($translation, '[' . $i . ']content')->widget(\mihaildev\ckeditor\CKEditor::class); ?>
                                <?= $form->field($translation, '[' . $i . ']lang_id')->hiddenInput(['value' => $translation->lang_id])->label(false) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block ' . ($page ? '' : 'hidden')]) ?>
            <div class="box box-default <?= $page ? 'hidden' : '' ?>">
                <div class="box-header with-border">Обшые настройки</div>
                <div class="box-body">
                    <?= $form->field($model, 'category_id')->dropDownList($model->categoriesList(), ['prompt' => 'No category'])->label('Катагория') ?>
                    <?php $model->date = $model->date ?: date('d.m.Y') ?>
                    <?= $form->field($model, 'date')->widget(\yii\jui\DatePicker::classname(), [
                        'dateFormat' => 'd.MM.yyyy',
                        'clientOptions' => [
                            // 'showButtonPanel'=>true,
                            'changeYear' => true,
                            'defaultDate' => date('Y-m-d')
                        ],
                        'options' => ['class' => 'form-control']
                    ])->label(Module::t('text', 'Date')) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>

            <div class="box box-default <?= $page ? 'hidden' : '' ?>">
                <div class="box-header with-border"><?= \domain\modules\text\Module::t('text', 'Photo') ?></div>
                <div class="box-body">
                    <?= $form->field($model, 'photo')
                        ->label(false)
                        ->widget(\kartik\file\FileInput::class, [
                            'options' => ['accept' => 'image/*'],
                            'pluginOptions' => [
                                'previewFileType' => 'image',
                                'showUpload' => false,
                                'initialPreview' => [
                                    '<img src="' . $thumb . '" style="max-height:200px; max-width:210px;">',
                                ],
                                'overwriteInitial' => true,
                                'initialCaption' => $thumb,
                            ],
                        ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
