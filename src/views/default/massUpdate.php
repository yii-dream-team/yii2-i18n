<?php
/**
 * @var View $this
 * @var SourceMessageSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var array $tabContent
 */

use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
use yiidreamteam\i18n\models\SourceMessageSearch;
use yiidreamteam\i18n\Module;

$this->title = Module::t('Translations');
echo Breadcrumbs::widget(['links' => [
    $this->title
]]);
?>
<div class="message-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?= yii\bootstrap\Nav::widget([
        'items' => $menuItems,
        'options' => [
            'class' => 'nav-tabs'
        ]
    ]) ?>

    <?php
    echo GridView::widget([
//    'id' => $language . '-message-grid',
//    'pjax' => true,
//    'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'value' => function ($model, $index, $dataColumn) {
                    return $model->id;
                },
                'filter' => false
            ],
            [
                'attribute' => 'sourceMessage.message',
                'value' => function($data) {
                    return $data->sourceMessage->message;
                }
            ],
            [
                'class' => kartik\grid\EditableColumn::className(),
                'attribute' => 'translation',
                'editableOptions'=> [
                    'header'=>'Перевод',
                    'size'=>'md',
                    'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
                    'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                    'formOptions' => [
                        'action' => \yii\helpers\Url::to(['save-translate'])
                    ]
                ]
            ],
            /*[
                'attribute' => 'category',
                'value' => function ($model, $index, $dataColumn) {
                    return $model->category;
                },
                'filter' => ArrayHelper::map($searchModel::getCategories(), 'category', 'category')
            ],*/
        ]
    ]);
    ?>
</div>
