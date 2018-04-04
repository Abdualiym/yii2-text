<?php

namespace abdualiym\text\entities;

use abdualiym\text\forms\CategoryForm;
use backend\entities\User;
use abdualiym\text\entities\queries\CategoryQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property boolean $feed_with_image
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property CategoryTranslation[] $translations
 */
class Category extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public $meta;

    public static function create($feed_with_image): self
    {
        $category = new static();
        $category->feed_with_image = $feed_with_image;
        $category->status = self::STATUS_DRAFT;
        return $category;
    }

    public function edit($feed_with_image)
    {
        $this->feed_with_image = $feed_with_image;
    }


    // Status

    public function activate()
    {
        if ($this->isActive()) {
            throw new \DomainException('Category is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if ($this->isDraft()) {
            throw new \DomainException('Category is already draft.');
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

    public function setTranslation($lang_id, $name, $title, $description, $meta)
    {
        $translations = $this->translations;
        foreach ($translations as $tr) {
            if ($tr->isForLanguage($lang_id)) {
                $tr->edit($name, $title, $description, $meta);
                $this->translations = $translations;
                return;
            }
        }
        $translations[] = CategoryTranslation::create($lang_id, $name, $title, $description, $meta);
        $this->translations = $translations;
    }

    public function getTranslation($id): CategoryTranslation
    {
        $translations = $this->translations;
        foreach ($translations as $tr) {
            if ($tr->isForLanguage($id)) {
                return $tr;
            }
        }
        return CategoryTranslation::blank($id);
    }


    ####################################

    public function getTranslations(): ActiveQuery
    {
        return $this->hasMany(CategoryTranslation::class, ['parent_id' => 'id']);
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

    public function getCategoryType()
    {
        return CategoryForm::getCategoryTypes($this->id);
    }


    ####################################

    public static function tableName(): string
    {
        return '{{%text_categories}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['translations'],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): CategoryQuery
    {
        return new CategoryQuery(static::class);
    }

}