<?php

use DevGroup\Media\models\Folder;
use DevGroup\Media\models\MediaProviders;
use yii\db\Migration;
use yii\helpers\Console;

class m170314_100448_base_tables extends Migration
{
    public function up()
    {
        switch (Yii::$app->db->driverName) {
            case 'mysql':
            case 'mariadb':
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                break;
            default:
                $tableOptions = null;
        }
        $this->createTable('{{%media_fs}}', [
            'id' => $this->primaryKey()->unsigned(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull()->unsigned(),
            'rgt' => $this->integer()->notNull()->unsigned(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'fs_path' => $this->string()->notNull(),
            'is_file' => $this->boolean()->notNull()->defaultValue(false),
            'created_time' => $this->timestamp()->null(),
            'updated_time' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex(
            'idx_fs_tree',
            '{{%media_fs}}',
            [
                'tree',
                'lft',
                'rgt',
                'depth',
                'is_file'
            ],
            false
        );

        $this->createTable('{{%media_file_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'class_name' => $this->string()->notNull(),
            'options' => $this->binary(),
        ]);

        $this->createTable('{{%media_file}}', [
            'file_id' => $this->integer()->notNull()->unsigned(),
            'size' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'public_url' => $this->text()->notNull(),
            'file_type_id' => $this->integer()->notNull()->defaultValue(0),
        ]);
        $this->addPrimaryKey('pFileId', '{{%media_file}}', 'file_id');
        $this->addForeignKey('mediaFileFolder', '{{%media_file}}', 'file_id', '{{%media_fs}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%media_image}}', [
            'file_id' => $this->integer()->notNull()->unsigned(),
            'extension' => $this->string()->notNull(),
            'width' => $this->integer()->notNull()->defaultValue(0),
            'height' => $this->integer()->notNull()->defaultValue(0),
            'thumb_file_id' => $this->integer()->notNull()->unsigned()->defaultValue(0),
        ]);
        $this->addPrimaryKey('piFileId', '{{%media_image}}', 'file_id');
        $this->addForeignKey('mediaImageFile', '{{%media_image}}', 'file_id', '{{%media_file}}', 'file_id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%media_file_relation}}', [
            'id' => $this->primaryKey()->unsigned(),
            'file_id' => $this->integer()->notNull()->unsigned(),
            'model_class_name_hash' => $this->char(32)->notNull(),
            'model_id' => $this->integer()->notNull(),
            'relation_name' => $this->string()->notNull()->defaultValue(''),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
        ]);
        $this->addForeignKey('mediaRel', '{{%media_file_relation}}', 'file_id', '{{%media_file}}', 'file_id', 'CASCADE', 'CASCADE');
        $this->createIndex('frFRel', '{{%file_relation}}', ['model_class_name_hash', 'model_id', 'file_id'], true);

        $this->createTable('{{%media_providers}}', [
            'id' => $this->primaryKey(),
            'class_name' => $this->string()->notNull(),
            'options' => $this->binary(),
            'tree_id' => $this->integer()->notNull(),
            'url_provider_class_name' => $this->string()->notNull(),
            'url_provider_options' => $this->binary(),
        ]);

        $localProvider = new MediaProviders([
            'class_name' => 'creocoder\flysystem\LocalFilesystem',
            'options' => [
                'path' => '@webroot/files',
            ],
            'url_provider_class_name' => 'DevGroup\Media\UrlProvider\LocalUrlProvider',
            'url_provider_options' => [],
        ]);
        $localProvider->loadDefaultValues();
        $localProvider->save();

        $tree = new Folder([
            'name' => 'Local',

        ]);
        $tree->loadDefaultValues();
        $tree->makeRoot();
    }

    public function down()
    {
        $this->dropTable('{{%media_providers}}');
        $this->dropTable('{{%media_file_relation}}');
        $this->dropTable('{{%media_image}}');
        $this->dropTable('{{%media_file}}');
        $this->dropTable('{{%media_file_type}}');
        $this->dropTable('{{%media_fs}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
