<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel domain\modules\text\forms\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

$feed_with_image = (new \domain\modules\text\forms\CategoryForm())->getAttributeLabel('feed_with_image');


?>
<div class="user-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => 'Название',
                        'value' => function (\domain\modules\text\entities\Category $model) {
                            return Html::a(Html::encode($model->translations[0]['name']), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'slug',
                        'label' => 'RU',
                        'value' => function (\domain\modules\text\entities\Category $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->params['frontendUrl'] . '/ru/' . Html::encode($model->translations[1]['slug']));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'slug',
                        'label' => 'UZ',
                        'value' => function (\domain\modules\text\entities\Category $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->params['frontendUrl'] . '/en/' . Html::encode($model->translations[0]['slug']));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'feed_with_image',
                        'value' => 'feed_with_image',
                        'format' => 'boolean',
                        'label' => $feed_with_image,
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
