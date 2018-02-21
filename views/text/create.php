<?php

/* @var $this yii\web\View */
/* @var $model domain\modules\text\forms\TextForm */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => \domain\modules\text\Module::t('text', $page ? 'Pages' : 'Articles'), 'url' => ['index', 'page' => $page]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?= $this->render('_form', [
        'model' => $model,
        'page' => $page,
    ]) ?>

</div>
