<?php

/* @var $this yii\web\View */
/* @var $text domain\modules\text\entities\Text */
/* @var $model domain\modules\text\forms\TextForm */

$this->title = 'Обновить: ' . $text->translations[0]['title'];
$this->params['breadcrumbs'][] = ['label' => \domain\modules\text\Module::t('text', $page ? 'Pages' : 'Articles'), 'url' => ['index', 'page' => $page]];
$this->params['breadcrumbs'][] = ['label' => $text->translations[0]['title'], 'url' => ['view', 'id' => $text->id, 'page' => $page]];
?>
<div class="text-update">

    <?= $this->render('_form', [
        'model' => $model,
        'text' => $text,
        'page' => $page,
    ]) ?>

</div>
