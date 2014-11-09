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
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            /*[
                'attribute' => 'id',
                'value' => function ($model, $index, $dataColumn) {
                    return $model->id;
                },
                'filter' => false
            ],*/
            [
                'attribute' => 'source',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->sourceMessage->message, ['update', 'id' => $model->sourceMessage->id], ['data' => ['pjax' => 0]]);
                }
            ],
            [
                'class' => kartik\grid\EditableColumn::className(),
                'attribute' => 'translation',
                'editableOptions' => [
                    'header' => 'Перевод',
                    'size' => 'md',
                    'placement' => \kartik\popover\PopoverX::ALIGN_TOP,
                    'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                    'formOptions' => [
                        'action' => \yii\helpers\Url::to(['save-translate'])
                    ],
                    'pluginEvents' => [
                        "editableSuccess" => "function(val) {
                            var id = val.currentTarget.id;
                            var row = $('#'+id).closest('tr');
                            row.next().find('.kv-editable-link').trigger('click');
                        }",
                    ]
                ]
            ],
            [
                'attribute' => 'category',
                'value' => function($data) {
                    return $data->sourceMessage->category;
                }
            ],
        ]
    ]);
    ?>
</div>
