<?php

use yii\helpers\Html;
use yii\bootstrap\BootstrapPluginAsset;
BootstrapPluginAsset::register($this);

/** @var  \yii\web\View $this */

$js = <<< 'JS'
$(document).ready(function() {
    showMissingTranslationsModal();
    saveMissingTranslations();
});
function showMissingTranslationsModal()
{
    var missingTranslations = $('#missingTranslationsModal, .missingTranslationsModalOpen').remove();
    $('body').append(missingTranslations);
    $('.missingTranslationsModalOpen').on('click', function() {
        $('.response').html('');
        $('#missingTranslationsModal').modal();
        return false;
    });
}
function saveMissingTranslations()
{
    $('.saveMissingTranslations').on('click', function() {
        var $form = $('#missingTranslationsForm');
        var data = [];
        $.ajax({
            url: $form.attr('action'),
            type: 'post',
            data: $form.serialize(),
            success: function(response) {
                $('.response').html(response);
            }
        });
        return false;
    });
}
JS;
$this->registerJs($js);
$css = <<< 'CSS'
.missingTranslationsModalOpen {
    position: absolute;
    left: 0px;
    bottom: 0px;
    z-index: 100000000000;
}
#missingTranslationsModal .modal-dialog {
    width: 90% !important;
    min-width: 90% !important;
}
#missingTranslationsModal .modal-content {
    overflow: hidden;
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
                <?php
                echo Html::beginForm($url, 'post', ['id' => 'missingTranslationsForm']);
                ?>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>
                                Категория, сообщение
                            </th>
                            <?php foreach ($languages as $language) : ?>
                                <th>
                                    Перевод для языка:
                                    <b>
                                        <?php echo $language; ?>
                                    </b>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <?php foreach ($missingTranslations as $missingTranslation) : ?>
                        <tr>
                            <td>
                                <?php
                                echo 'Категория: <b>' . $missingTranslation['category'] . '</b>, сообщение: <b>' .
                                    $missingTranslation['message'] . '</b>';
                                ?>
                            </td>
                            <?php foreach ($missingTranslation['messages'] as $message) : ?>
                                <td>
                                    <?php
                                    echo Html::input('text',
                                        'messages[' . $message->id . '::' . $message->language . ']',
                                        $message->translation
                                    );
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php echo Html::endForm(); ?>
                <div class="response row"></div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Перевести', ['class' => 'btn btn-primary saveMissingTranslations']) ?>
                <?= Html::button('Закрыть', ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
    </div>
</div>