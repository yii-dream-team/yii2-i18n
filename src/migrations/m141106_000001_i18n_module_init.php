<?php

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\db\Schema;

class m141106_000001_i18n_module_init extends Migration
{
    public function up()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->createTable($sourceMessageTable, [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING.'(32) DEFAULT NULL',
            'message' => Schema::TYPE_TEXT
        ]);

        $this->createTable($messageTable, [
            'sourceMessageId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'language' => Schema::TYPE_STRING . '(16) NOT NULL',
            'translation' => Schema::TYPE_TEXT
        ]);
        $this->addPrimaryKey('id', $messageTable, ['sourceMessageId', 'language']);
        $this->addForeignKey('FK_message_sourceMessageId', $messageTable, 'sourceMessageId', $sourceMessageTable, 'id', 'cascade');
    }

    public function down()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->dropForeignKey('FK_message_sourceMessageId', $messageTable);
        $this->dropTable($messageTable);
        $this->dropTable($sourceMessageTable);
    }
}
