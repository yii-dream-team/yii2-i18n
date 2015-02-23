<?php
/**
 * @var View $this
 * @var SourceMessage $model
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use yiidreamteam\i18n\models\SourceMessage;
use yiidreamteam\i18n\backend\I18n;

$this->title = I18n::t('Update') . ': ' . $model->message;
echo Breadcrumbs::widget(['links' => [
    ['label' => I18n::t('Translations'), 'url' => ['index']],
    ['label' => $this->title]
]]);
?>
<div class="message-update">
    <div class="message-form">
        <div class="panel panel-default">
            <div class="panel-heading"><?= I18n::t('Source message') ?></div>
            <div class="panel-body"><?= Html::encode($model->message) ?></div>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <?php foreach ($model->messages as $language => $message) : ?>
                <?= $form->field($model->messages[$language], '[' . $language . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textInput()->label($language) ?>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <?=
            Html::submitButton(
                $model->getIsNewRecord() ? I18n::t('Create') : I18n::t('Update'),
                ['class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary']
            ) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>
