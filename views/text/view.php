<?php

use abdualiym\text\Module;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $text abdualiym\text\entities\Text */

$this->title = $text->translations[1]['title'];
$this->params['breadcrumbs'][] = ['label' => \abdualiym\text\Module::t('text', $page ? 'Pages' : 'Articles'), 'url' => ['index', 'page' => $page]];
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
                                    : ''
                                ;
                        },
                        'label' => Module::t('text', 'Category')
                    ],
                    [
                        'attribute' => 'status',
                        'value' => \abdualiym\text\helpers\TextHelper::statusLabel($text->status),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'date',
                        'format' => 'date',
                        'label' => Module::t('text', 'Date')
                    ],
                    [
                        'attribute' => 'is_article',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->is_article ? Module::t('text', 'Article') : Module::t('text', 'Page');
                        },
                        'label' => Module::t('text', 'Type')
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border"><?= Module::t('text', 'Photo') ?></div>
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
                                        'label' => Module::t('text', 'Title')
                                    ],
                                    [
                                        'attribute' => 'slug',
                                        'label' => Module::t('text', 'Slug')
                                    ],
                                    [
                                        'attribute' => 'description',
                                        'label' => Module::t('text', 'Description')
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
                'label' => Module::t('text', 'Created by')
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => Module::t('text', 'Created at')
            ],
            [
                'attribute' => 'updatedBy.username',
                'label' => Module::t('text', 'Updated by')
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'label' => Module::t('text', 'Updated at')
            ],
        ],
    ]) ?>

</div>
