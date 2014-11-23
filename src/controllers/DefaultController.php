<?php

namespace yiidreamteam\i18n\controllers;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yiidreamteam\i18n\models\MessageSearch;
use yiidreamteam\i18n\models\SourceMessage;
use yiidreamteam\i18n\models\Message;
use yiidreamteam\i18n\Module;

class DefaultController extends Controller
{
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

    public function actionIndex($language = 'en-EN')
    {
        $searchModel = new MessageSearch;
        $searchModel->language = $language;
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());

        $menuItems = [];
        foreach(\Yii::$app->i18n->languages as $lang => $label) {
            $menuItems[] = [
                'label' => $label,
                'url' => Url::to(['index', 'language' => $lang]),
                'active' => $lang == $language
            ];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'language' => $language,
            'menuItems' => $menuItems
        ]);
    }

    public function actionSaveTranslate()
    {
        if(!Yii::$app->request->post('hasEditable', false))
            return;

        $key = unserialize(Yii::$app->request->post('editableKey', false));
        if(empty($key))
            return;

        /** @var Message $model */
        $model = Message::findOne($key);
        if(Model::loadMultiple([Yii::$app->request->post('editableIndex', 0) => $model], Yii::$app->request->post()) && $model->save())
        {
            echo Json::encode(['output' => Html::encode($model->translation)]);
        } else
            echo Json::encode(['message' => 'Ошибки при вводе']);
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
