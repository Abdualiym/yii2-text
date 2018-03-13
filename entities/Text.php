<?php

namespace abdualiym\text\entities;

use abdualiym\languageClass\Language;
use backend\entities\User;
use domain\modules\menu\entities\Menu;
use abdualiym\text\entities\queries\TextQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property integer $category_id
 * @property boolean $is_article
 * @property integer $status
 * @property integer $date
 * @property string $photo
 * @property integer $views_count
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property TextTranslation[] $translations
 */
class Text extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

//    public $meta;

    public static function create($category_id, $date): self
    {
        $text = new static();
        $text->category_id = $category_id;
        $text->date = $date;
        $text->status = self::STATUS_DRAFT;
        return $text;
    }

    public function edit($category_id, $date)
    {
        $this->category_id = $category_id;
        $this->date = $date;
    }


    public function setPhoto(UploadedFile $photo)
    {
        $this->photo = $photo;
    }

    // Status

    public function activate()
    {
        if ($this->isActive()) {
            throw new \DomainException('Text is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if ($this->isDraft()) {
            throw new \DomainException('Text is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }


// Translations

    public function setTranslation($lang_id, $title, $description, $content, $meta)
    {
        $translations = $this->translations;
        foreach ($translations as $tr) {
            if ($tr->isForLanguage($lang_id)) {
                $tr->edit($title, $description, $content, $meta);
                $this->translations = $translations;
                return;
            }
        }
        $translations[] = TextTranslation::create($lang_id, $title, $description, $content, $meta);
        $this->translations = $translations;
    }

    public function getTranslation($id): TextTranslation
    {
        $translations = $this->translations;
        foreach ($translations as $tr) {
            if ($tr->isForLanguage($id)) {
                return $tr;
            }
        }
        return TextTranslation::blank($id);
    }


    ####################################

    public function getTranslations(): ActiveQuery
    {
        return $this->hasMany(TextTranslation::class, ['parent_id' => 'id']);
    }

    public function getMetaFields(): ActiveQuery
    {
        return $this->hasMany(TextMetaFields::class, ['text_id' => 'id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getUpdatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    ####################################

    public static function tableName(): string
    {
        return '{{%text_texts}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date'],
                ],
                'value' => function () {
                    return is_integer($this->date) ? $this->date : (int)strtotime($this->date);
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['is_article'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['is_article'],
                ],
                'value' => function () {
                    return $this->category_id ? true : false;
                },
            ],
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['translations'],
            ],
            [
                'class' => ImageUploadBehavior::className(),
                'attribute' => 'photo',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/app/text/[[attribute_id]]/[[id]].[[extension]]',
                'fileUrl' => '@staticUrl/app/text/[[attribute_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/app/cache/text/[[attribute_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@staticUrl/app/cache/text/[[attribute_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'category_list' => ['width' => 1000, 'height' => 150],
                    'widget_list' => ['width' => 228, 'height' => 228],
//                    'origin' => ['processor' => [new WaterMarker(1024, 768, '@frontend/web/images/img/cbg.png'), 'process']],
                ],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): TextQuery
    {
        return new TextQuery(static::class);
    }

}