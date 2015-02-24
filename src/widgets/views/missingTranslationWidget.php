<?php

use yii\helpers\Html;
use yii\bootstrap\BootstrapPluginAsset;
BootstrapPluginAsset::register($this);

/** @var  \yii\web\View $this */

$js = <<< JS
$(document).ready(function() {
    showMissingTranslationsModal();
});
function showMissingTranslationsModal()
{
    var missingTranslationsModal = $('#missingTranslationsModal').remove();
    $('body').append(missingTranslationsModal);
    $('.missingTranslationsModalOpen').on('click', function() {
        $('#missingTranslationsModal').modal();
        return false;
    });
}
JS;
$this->registerJs($js);
$css = <<< CSS
.missingTranslationsModalOpen {
    position: absolute;
    left: 0px;
    bottom: 0px;
    z-index: 100000000000;
}
CSS;

$this->registerCss($css);
?>
<button class="btn btn-primary missingTranslationsModalOpen">
    Переводы
</button>
<div class="modal"
     id="missingTranslationsModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="missingTranslationsModalLabel"
     aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
                <h4 class="modal-title">
                    Не переведенные сообщения
                </h4>
            </div>
            <div class="modal-body">
                <?php echo Html::beginForm('missing-translation/index'); ?>
                <table class="table table-hover table-bordered">
                    <?php foreach ($missingTranslations as $missingTranslation) : ?>
                        <tr>
                            <td>
                                <?php echo $currentLanguage; ?>
                            </td>
                            <td>
                                <?php
                                echo 'Категория: <b>' . $missingTranslation['category'] . '</b>, сообщение: <b>' .
                                    $missingTranslation['message'] . '</b>';
                                ?>
                            </td>
                            <td>
                                <?php
                                echo Html::input('text', null, null, ['placehoder' => 'текст перевода']);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php echo Html::endForm(); ?>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Перевести', ['class' => 'btn btn-primary']) ?>
                <?= Html::button('Закрыть', ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
    </div>
</div>