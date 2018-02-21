<?php

use abdualiym\languageClass\Language;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model abdualiym\text\forms\CategoryForm */
/* @var $category abdualiym\text\entities\Category */

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
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">

        <div class="box-header with-border">Категория</div>
        <div class="box-body">
            <?= $form->errorSummary($model) ?>
            <?= $form->field($model, 'feed_with_image')->dropDownList([0 => 'по умолчанию', 1 => 'без даты', 2 => 'без списка']) ?>
        </div>

        <div class="box-body">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($model->translations as $i => $translation): ?>
                    <li role="presentation" <?= $i == 0 ? 'class="active"' : '' ?>>
                        <a href="#<?= $langList[$translation->lang_id]['prefix'] ?>" aria-controls="<?= $langList[$translation->lang_id]['prefix'] ?>" role="tab" data-toggle="tab">
                            <?= '(' . $langList[$translation->lang_id]['prefix'] . ') ' . $langList[$translation->lang_id]['title'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <br>
                <?php foreach ($model->translations as $i => $translation): ?>
                    <div role="tabpanel" class="tab-pane <?= $i == 0 ? 'active' : '' ?>" id="<?= $langList[$translation->lang_id]['prefix'] ?>">
                        <?= $form->field($translation, '[' . $i . ']name')->textInput(['maxlength' => true])->label("Название на (" . $langList[$translation->lang_id]['title'] . ")") ?>
                        <?php //= $form->field($model->translations, 'slug[' . $i . ']')->textInput(['maxlength' => true]) ?>
                        <?php //= $form->field($model->translations, 'title[' . $i . ']')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($translation, '[' . $i . ']description')->widget(\mihaildev\ckeditor\CKEditor::className()); ?>
                        <?= $form->field($translation, '[' . $i . ']lang_id')->hiddenInput(['value' => $langList[$translation->lang_id]['id']])->label(false) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
