<?php

namespace DevGroup\Media\models;

use Yii;

/**
 * This is the model class for table "{{%media_file}}".
 *
 * @property int $file_id
 * @property string $size
 * @property string $public_url
 * @property int $file_type_id
 * @property MediaFs $file
 * @property MediaFileRelation[] $mediaFileRelations
 * @property MediaImage $mediaImage
 */
class MediaFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'public_url'], 'required'],
            [['file_id', 'size', 'file_type_id'], 'integer'],
            [['public_url'], 'string'],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => MediaFs::className(), 'targetAttribute' => ['file_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => Yii::t('yii2-media', 'File ID'),
            'size' => Yii::t('yii2-media', 'Size'),
            'public_url' => Yii::t('yii2-media', 'Public Url'),
            'file_type_id' => Yii::t('yii2-media', 'File Type ID'),
        ];
    }

    /*
    * @return \yii\db\ActiveQuery
    */
    public function getFile()
    {
        return $this->hasOne(MediaFs::className(), ['id' => 'file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaFileRelations()
    {
        return $this->hasMany(MediaFileRelation::className(), ['file_id' => 'file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaImage()
    {
        return $this->hasOne(MediaImage::className(), ['file_id' => 'file_id']);
    }


    /**
     * @inheritdoc
     * @return MediaFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaFileQuery(get_called_class());
    }
}
