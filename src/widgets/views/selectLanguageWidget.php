<?php 

use yii\widgets\ActiveForm;
use yii\helpers\Html;
/**
 * @author ValentinK <rlng-krsk@yandex.ru>
 */
?>

<?php $form = ActiveForm::begin([
	'id' => $this->context->id,
	'action' => $formAction,
	'method' => 'GET'
]); ?>
<?= Html::dropDownList(Yii::$app->i18n->languageParam, $selectedLanguage, $languages, $options = [
		'onchange' => 'this.form.submit()'
	] ) ?>
<?php ActiveForm::end(); ?>