<?php

namespace DevGroup\Media\models;

use Yii;

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
    }
}
