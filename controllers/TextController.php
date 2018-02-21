<?php

namespace abdualiym\text\controllers;

use abdualiym\text\entities\Text;
use abdualiym\text\forms\TextForm;
use abdualiym\text\forms\TextSearch;
use abdualiym\text\services\TextManageService;
use Yii;
use yii\base\ViewContextInterface;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TextController extends Controller implements ViewContextInterface
{
    private $service;

    public function __construct($id, $module, TextManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                ],
            ],
        ];
    }


    public function getViewPath()
    {
        return Yii::getAlias('@vendor/abdualiym/yii2-text/views/text');
    }


    public function actionIndex($page = false)
    {
        $searchModel = new TextSearch($page);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $page = false)
    {
        return $this->render('view', [
            'text' => $this->findModel($id),
            'page' => $page,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate($page = false)
    {
        $form = new TextForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $text = $this->service->create($form);
                return $this->redirect(['view', 'id' => $text->id, 'page' => $page]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
            'page' => $page,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $page = false)
    {
        $text = $this->findModel($id);
        $form = new TextForm($text);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $this->service->edit($text->id, $form);
                return $this->redirect(['view', 'id' => $text->id, 'page' => $page]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'text' => $text,
            'page' => $page,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $idText
     * @return mixed
     */
    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param integer $id
     * @return Text the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Text
    {
        if (($model = Text::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested text does not exist.');
    }
}
