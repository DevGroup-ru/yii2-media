<?php

namespace DevGroup\Media\models;

use Yii;

/**
 * This is the model class for table "{{%media_file_relation}}".
 *
 * @property int $id
 * @property int $file_id
 * @property string $model_class_name_hash
 * @property int $model_id
 * @property string $relation_name
 * @property int $sort_order
 *
 * @property MediaFile $file
 */
class MediaFileRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_file_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'model_class_name_hash', 'model_id'], 'required'],
            [['file_id', 'model_id', 'sort_order'], 'integer'],
            [['model_class_name_hash'], 'string', 'max' => 32],
            [['relation_name'], 'string', 'max' => 255],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => MediaFile::className(), 'targetAttribute' => ['file_id' => 'file_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii2-media', 'ID'),
            'file_id' => Yii::t('yii2-media', 'File ID'),
            'model_class_name_hash' => Yii::t('yii2-media', 'Model Class Name Hash'),
            'model_id' => Yii::t('yii2-media', 'Model ID'),
            'relation_name' => Yii::t('yii2-media', 'Relation Name'),
            'sort_order' => Yii::t('yii2-media', 'Sort Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(MediaFile::className(), ['file_id' => 'file_id']);
    }

    /**
     * @inheritdoc
     * @return MediaFileRelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaFileRelationQuery(get_called_class());
    }
}
