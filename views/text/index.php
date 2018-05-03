<?php

use abdualiym\menu\components\MenuSlugHelper;
use abdualiym\languageClass\Language;
use abdualiym\text\entities\Text;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel abdualiym\text\forms\TextSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('text', $page ? 'Pages' : 'Articles');
$this->params['breadcrumbs'][] = $this->title;

$columns = [];
if (!$page) {
//    $columns[] = [
//        'value' => function (Text $model) {
//            return $model->photo ? Html::img($model->getThumbFileUrl('photo', 'admin')) : null;
//        },
//        'format' => 'raw',
//        'contentOptions' => ['style' => 'width: 100px'],
//    ];
    $columns[] = [
        'value' => function (Text $model) {
            return $model->mainPhoto ? Html::img($model->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
        },
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 100px'],
    ];
    $columns[] =
        [
            'attribute' => 'category_id',
            'label' => Yii::t('text', 'Category'),
            'value' => function (Text $model) {
                return $model->category ? $model->category->translations[0]['name'] : 'No';
            },
        ];
    $columns[] =
        [
            'attribute' => 'date',
            'label' => Yii::t('text', 'Date'),
            'format' => 'date',
        ];

}
$columns[] = [
    'attribute' => 'id',
    'label' => 'Название',
    'value' => function (Text $model) {
        foreach ($model->translations as $translation) {
            if ($translation['lang_id'] == (Language::getLangByPrefix('ru'))['id']) {
                $translate = $translation;
            }
        }
        return Html::a(Html::encode($translate['title']), ['view', 'id' => $model->id, 'page' => (Yii::$app->request->get('page') ? true : false)]);
    },
    'format' => 'raw',
];
$columns[] =
    [
        'attribute' => 'status',
        'label' => Yii::t('text', 'Status'),
        'value' => function (Text $model) {
            return \abdualiym\text\helpers\TextHelper::statusLabel($model->status);
        },
        'format' => 'html',
    ];
?>
<div class="user-index">

    <p>
        <?= Html::a('Добавить', ['create', 'page' => $page], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => $columns]) ?>
        </div>
    </div>
</div>
