<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use abdualiym\menu\components\MenuSlugHelper;
use \abdualiym\text\entities\Category;


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
                        'attribute' => 'slug',
                        'label' => 'RU',
                        'value' => function (Category $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->params['frontendUrl'] . '/ru' . Html::encode(MenuSlugHelper::getSlug($model->translations[0]['slug'], 'category', $model->id, Language::getLangByPrefix('ru'))), ['target' => '_blank']);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'slug',
                        'label' => 'UZ',
                        'value' => function (Category $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->params['frontendUrl'] . '/uz' . Html::encode(MenuSlugHelper::getSlug($model->translations[1]['slug'], 'category', $model->id, Language::getLangByPrefix('uz'))), ['target' => '_blank']);
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
