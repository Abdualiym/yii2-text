<?php

use abdualiym\languageClass\Language;
use abdualiym\menu\components\MenuSlugHelper;
use abdualiym\text\entities\Category;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel abdualiym\text\forms\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

$feed_with_image = (new \abdualiym\text\forms\CategoryForm())->getAttributeLabel('feed_with_image');


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
                        'value' => function (Category $model) {
                            return Html::a(Html::encode($model->translations[0]['name']), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'feed_with_image',
                        'value' => function ($model) {
                            return $model->getCategoryType();
                        },
                        'format' => 'boolean',
                        'label' => $feed_with_image,
                    ],
                    [
                        'attribute' => 'status',
                        'label' => Yii::t('text', 'Status'),
                        'value' => function (Category $model) {
                            return \abdualiym\text\helpers\TextHelper::statusLabel($model->status);
                        },
                        'format' => 'html',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
