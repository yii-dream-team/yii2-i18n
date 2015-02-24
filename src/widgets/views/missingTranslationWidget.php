<?php
use yii\helpers\Html;
?>
<div class="modal" id="showNewsModal" tabindex="-1" role="dialog" aria-labelledby="showNewsModalLabel" aria-hidden="false">
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
                <table class="table table-hover table-bordered">
                    <?php foreach ($missingTranslations as $missingTranslation) : ?>
                        <tr>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Перевести', ['class' => 'btn btn-primary']) ?>
                <?= Html::button('Закрыть', ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
    </div>
</div>
