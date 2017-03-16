<?php

namespace DevGroup\Media\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%media_fs}}".
 *
 * @property int $id
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $name
 * @property string $fs_path
 * @property int $is_file
 * @property string $created_time
 * @property string $updated_time
 * @mixin NestedSetsBehavior
 *
 */
class MediaFs extends \yii\db\ActiveRecord
{
    use \DevGroup\TagDependencyHelper\TagDependencyTrait;

    const TYPE_FOLDER = 'folder';
    const TYPE_FILE = 'file';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'CacheableActiveRecord' => [
                'class' => \DevGroup\TagDependencyHelper\CacheableActiveRecord::class,
            ],
            'tree' => [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_fs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['tree', 'lft', 'rgt', 'depth'], 'default', 'value' => 0],
            [['tree', 'lft', 'rgt', 'depth', 'is_file'], 'integer'],
            [['name', 'fs_path'], 'string', 'max' => 255],
            [['name', 'fs_path'], 'default', 'value' => ''],
            [['created_time', 'updated_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii2-media', 'ID'),
            'tree' => Yii::t('yii2-media', 'Tree'),
            'lft' => Yii::t('yii2-media', 'Lft'),
            'rgt' => Yii::t('yii2-media', 'Rgt'),
            'depth' => Yii::t('yii2-media', 'Depth'),
            'name' => Yii::t('yii2-media', 'Name'),
            'fs_path' => Yii::t('yii2-media', 'Fs Path'),
            'is_file' => Yii::t('yii2-media', 'Is File'),
            'created_time' => Yii::t('yii2-media', 'Created Time'),
            'updated_time' => Yii::t('yii2-media', 'Updated Time'),
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     * @return MediaFsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaFsQuery(get_called_class());
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ((int) $this->depth === 0) {
            Yii::$app->cache->delete('Media:FileSystem:Trees');
        }
    }
}
