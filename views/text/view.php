<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $text abdualiym\text\entities\Text */

$this->title = $text->translations[1]['title'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('text', $page ? 'Pages' : 'Articles'), 'url' => ['index', 'page' => $page]];
$this->params['breadcrumbs'][] = $this->title;

$langList = \abdualiym\languageClass\Language::langList(Yii::$app->params['languages'], true);

?>
<div class="user-view">

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $text->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Обновить', ['update', 'id' => $text->id, 'page' => $page], ['class' => 'btn btn-primary']) ?>
        <?php if ($text->isActive()): ?>
            <?= Html::a(Yii::t('app', 'Draft'), ['draft', 'id' => $text->id], ['class' => 'btn btn-default pull-right', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('app', 'Activate'), ['activate', 'id' => $text->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
    </p>

    <div class="row <?= $page ? 'hidden' : '' ?>">
        <div class="col-sm-6">
            <?= DetailView::widget([
                'model' => $text,
                'attributes' => [
                    'id',
                    ['attribute' => 'id',
                        'value' => function ($model) {
                            return
                                isset($model->category->translations[0]) ?
                                    $model->category->translations[0]->name
                                    : '';
                        },
                        'label' => Yii::t('text', 'Category')
                    ],
                    [
                        'attribute' => 'status',
                        'value' => \abdualiym\text\helpers\TextHelper::statusLabel($text->status),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'date',
                        'format' => 'date',
                        'label' => Yii::t('text', 'Date')
                    ],
                    [
                        'attribute' => 'is_article',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->is_article ? Yii::t('text', 'Article') : Yii::t('text', 'Page');
                        },
                        'label' => Yii::t('text', 'Type')
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border"><?= Yii::t('text', 'Photo') ?></div>
                <div class="box-body">
                    <?php if ($text->photo): ?>
                        <?= Html::a(Html::img($text->getThumbFileUrl('photo', 'thumb')), $text->getUploadedFileUrl('photo'), [
                            'class' => 'thumbnail',
                            'target' => '_blank'
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">

        <div class="box-header with-border">Контент</div>

        <div class="box-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php
                $j = 0;
                foreach ($text->translations as $i => $translation) {
                    if (isset($langList[$translation->lang_id])) {
                        $j++;
                        ?>
                        <li role="presentation" <?= $j === 1 ? 'class="active"' : '' ?>>
                            <a href="#<?= $langList[$translation->lang_id]['prefix'] ?>"
                               aria-controls="<?= $langList[$translation->lang_id]['prefix'] ?>"
                               role="tab" data-toggle="tab">
                                <?= '(' . $langList[$translation->lang_id]['prefix'] . ') ' . $langList[$translation->lang_id]['title'] ?>
                            </a>
                        </li>
                    <?php }
                }
                ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <br>
                <?php
                $j = 0;
                foreach ($text->translations as $i => $translation) {
                    if (isset($langList[$translation->lang_id])) {
                        $j++;
                        ?>
                        <div role="tabpanel" class="tab-pane <?= $j === 1 ? 'active' : '' ?>"
                             id="<?= $langList[$translation->lang_id]['prefix'] ?>">
                            <?= DetailView::widget(['model' => $translation,
                                'attributes' => [
                                    [
                                        'attribute' => 'title',
                                        'label' => Yii::t('text', 'Title')
                                    ],
                                    [
                                        'attribute' => 'slug',
                                        'label' => Yii::t('text', 'Slug')
                                    ],
                                    [
                                        'attribute' => 'description',
                                        'label' => Yii::t('text', 'Description')
                                    ],
                                ],
                            ]) ?>

                            <?= $translation->content ?>

                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>

    <?= DetailView::widget(['model' => $text,
        'attributes' => [
            [
                'attribute' => 'createdBy.username',
                'label' => Yii::t('text', 'Created by')
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => Yii::t('text', 'Created at')
            ],
            [
                'attribute' => 'updatedBy.username',
                'label' => Yii::t('text', 'Updated by')
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'label' => Yii::t('text', 'Updated at')
            ],
        ],
    ]) ?>


    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $metaFieldProvider,
                'filterModel' => $searchMetaFieldModel,
                'columns' => [
                    ['class' => \yii\grid\SerialColumn::class],
                    'lang_id',
                    'key',
                    'value',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-edit"></span>', [
                                    'text/meta-update', 'id' => $model->id
                                ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<span class="fa fa-times-circle"></span>',
                                    [
                                        'text/meta-delete',
                                        'id' => $model->id
                                    ],
                                    [
                                        'options' => ['target' => '_blank'],
                                        'data' => [
                                            'confirm' => 'Вы уверены?',
                                            'method' => 'post',
                                        ]
                                    ]);
                            },
                        ],
                    ],
                ]
            ]) ?>
        </div>
    </div>


    <?php $form = ActiveForm::begin(['action' => ['meta-create', 'id' => $text->id]]); ?>
    <?= $form->errorSummary($meta) ?>
    <div class="box box-default <?= $page ? 'hidden' : '' ?>">
        <div class="box-header with-border">Добавить новую</div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-2">
                    <?= $form->field($meta, 'lang_id')->dropDownList(ArrayHelper::map($langList, 'id', 'title'), ['prompt' => 'Для всех языков']) ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($meta, 'key')->textInput() ?>
                </div>
                <div class="col-sm-5">
                    <?= $form->field($meta, 'value')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($meta, 'text_id')->hiddenInput(['value' => $text->id])->label(false) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
