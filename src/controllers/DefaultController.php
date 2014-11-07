<?php

namespace yiidreamteam\i18n\controllers;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yiidreamteam\i18n\models\SourceMessageSearch;
use yiidreamteam\i18n\models\SourceMessage;
use yiidreamteam\i18n\models\Message;
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

    public function actionMassUpdate($language = 'en-EN')
    {
        $searchModel = new ActiveDataProvider(['query' => Message::find()->where(['language' => $language])]);
        $dataProvider = $searchModel;

        $menuItems = [];
        foreach(\Yii::$app->i18n->languages as $lang) {
            $menuItems[] = [
                'label' => $lang,
                'url' => Url::to(['mass-update', 'language' => $lang]),
                'active' => $lang == $language
            ];
        }

        return $this->render('massUpdate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'language' => $language,
            'menuItems' => $menuItems
        ]);
    }

    public function actionSaveTranslate()
    {
        $key = unserialize(Yii::$app->request->post('editableKey', false));
        if(empty($key))
            return;

        $model = Message::findOne($key);
        $models = [$_POST['editableIndex'] => $model];
        if(Model::loadMultiple($models, Yii::$app->request->post()) && $model->save())
        {
            echo Json::encode(true);
        } else
            echo Json::encode(false);
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
