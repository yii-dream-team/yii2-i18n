<?php

namespace yiidreamteam\i18n\controllers;

use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yiidreamteam\i18n\models\SourceMessageSearch;
use yiidreamteam\i18n\models\SourceMessage;
use yiidreamteam\i18n\Module;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new SourceMessageSearch;
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param integer $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        /** @var SourceMessage $model */
        $model = $this->findModel($id);
        $model->initMessages();

        if (Model::loadMultiple($model->messages, Yii::$app->getRequest()->post()) && Model::validateMultiple($model->messages)) {
            $model->saveMessages();
            Yii::$app->getSession()->setFlash('success', Module::t('Updated'));
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionMassUpdate()
    {
        $searchModel = new SourceMessageSearch;

        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());

        $languages = \Yii::$app->i18n->languages;
        $tabContent = [];
        foreach ($languages as $i => $language) {
            $tabContent = [
                'title' => $language,
                'active' => !$i,
                'content' => $this->render('_massItem', [
                    'language' => $language,
                ])
            ];
        }

        return $this->render('massUpdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param array|integer $id
     * @return SourceMessage|SourceMessage[]
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $query = SourceMessage::find()->where('id = :id', [':id' => $id]);
        $models = is_array($id)
            ? $query->all()
            : $query->one();
        if (!empty($models)) {
            return $models;
        } else {
            throw new NotFoundHttpException(Module::t('The requested page does not exist'));
        }
    }
}
