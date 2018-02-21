<?php

namespace domain\modules\text\services;


use domain\modules\text\entities\Category;
use domain\modules\text\forms\CategoryForm;
use domain\modules\text\repositories\CategoryRepository;
use domain\modules\text\repositories\CategoryTranslationRepository;

class CategoryManageService
{
    private $categories;
    private $transaction;

    public function __construct(
        CategoryRepository $categories,
        TransactionManager $transaction
    )
    {
        $this->categories = $categories;
        $this->transaction = $transaction;
    }

    /**
     * @param CategoryForm $form
     * @return Category
     */
    public function create(CategoryForm $form): Category
    {
        $category = Category::create($form->feed_with_image);

        foreach ($form->translations as $translation) {
            $category->setTranslation($translation->lang_id, $translation->name, $translation->title, $translation->description, $translation->meta);
        }

        $this->categories->save($category);

        return $category;
    }

    public function edit($id, CategoryForm $form)
    {
        $category = $this->categories->get($id);

        $category->edit($form->feed_with_image);

        foreach ($form->translations as $translation) {
            $category->setTranslation($translation->lang_id, $translation->name, $translation->title, $translation->description, $translation->meta);
        }

        $this->categories->save($category);
    }

    public function activate($id)
    {
        $category = $this->categories->get($id);
        $category->activate();
        $this->categories->save($category);
    }

    public function draft($id)
    {
        $category = $this->categories->get($id);
        $category->draft();
        $this->categories->save($category);
    }

    public function remove($id)
    {
        $category = $this->categories->get($id);
        $this->categories->remove($category);
    }
}