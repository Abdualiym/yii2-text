<?php

namespace domain\modules\text\services;


use domain\modules\text\entities\Text;
use domain\modules\text\forms\TextForm;
use domain\modules\text\repositories\TextRepository;
use domain\modules\text\repositories\TextTranslationRepository;

class TextManageService
{
    private $textTranslations;
    private $texts;
    private $transaction;

    public function __construct(
        TextRepository $texts,
        TransactionManager $transaction
    )
    {
        $this->texts = $texts;
        $this->transaction = $transaction;
    }

    /**
     * @param TextForm $form
     * @return Text
     */
    public function create(TextForm $form): Text
    {
        $text = Text::create($form->category_id, $form->date);

        foreach ($form->translations as $translation) {
            $text->setTranslation($translation->lang_id, $translation->title, $translation->description, $translation->content, $translation->meta);
        }

        if ($form->photo) {
            $text->setPhoto($form->photo);
        }

        $this->texts->save($text);

        return $text;
    }

    public function edit($id, TextForm $form)
    {
        $text = $this->texts->get($id);

        $text->edit(
            $form->category_id,
            $form->date
        );

        foreach ($form->translations as $translation) {
            $text->setTranslation($translation->lang_id, $translation->title, $translation->description, $translation->content, $translation->meta);
        }

        if ($form->photo) {
            $text->setPhoto($form->photo);
        }

        $this->texts->save($text);
    }

    public function activate($id)
    {
        $text = $this->texts->get($id);
        $text->activate();
        $this->texts->save($text);
    }

    public function draft($id)
    {
        $text = $this->texts->get($id);
        $text->draft();
        $this->texts->save($text);
    }

    public function remove($id)
    {
        $text = $this->texts->get($id);
        $this->texts->remove($text);
    }
}