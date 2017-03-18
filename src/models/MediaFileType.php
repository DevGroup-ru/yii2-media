<?php

namespace DevGroup\Media\models;

use DevGroup\Media\FileType\AbstractFileType;
use Yii;
use yii\caching\TagDependency;

/**
 * This is the model class for table "{{%media_file_type}}".
 *
 * @property int $id
 * @property string $name
 * @property string $class_name
 * @property resource $options
 */
class MediaFileType extends \yii\db\ActiveRecord
{
    use \DevGroup\TagDependencyHelper\TagDependencyTrait;

    /** @var AbstractFileType */
    public $handler;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_file_type}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'CacheableActiveRecord' => [
                'class' => \DevGroup\TagDependencyHelper\CacheableActiveRecord::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_name'], 'required'],
            [['options'], 'string'],
            [['name', 'class_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii2-media', 'ID'),
            'name' => Yii::t('yii2-media', 'Name'),
            'class_name' => Yii::t('yii2-media', 'Class Name'),
            'options' => Yii::t('yii2-media', 'Options'),
        ];
    }

    /**
     * @inheritdoc
     * @return MediaFileTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaFileTypeQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        $this->options = json_encode($this->options);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->options = json_decode($this->options);
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->options = json_decode($this->options);

        $options = $this->options;
        $options['class'] = $this->class_name;
        $this->handler = Yii::createObject($options);
    }

    /**
     * @param int $id
     *
     * @return MediaFileType
     */
    public static function findById($id)
    {
        static::preload();
        return static::loadModel($id, false, true, 86400, true, true);
    }

    public static function preload()
    {
        if (count(static::$identityMap) === 0) {
            static::$identityMap = Yii::$app->cache->getOrSet(
                'Media:FileTypes:All',
                function() {
                    return static::find()
                        ->indexBy('id')
                        ->orderBy(['id' => SORT_ASC])
                        ->all();
                },
                86400,
                new TagDependency([
                    'tags' => [
                        static::commonTag()
                    ]
                ])
            );
        }
    }

    public static function defineFileType($filename)
    {
        static::preload();
        foreach (static::$identityMap as $model)
        {
            /** @var MediaFileType $model */
            if ($model->handler->checkFileType($filename)) {
                return $model;
            }
        }

        return reset(static::$identityMap);
    }
}
