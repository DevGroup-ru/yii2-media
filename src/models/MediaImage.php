<?php

namespace DevGroup\Media\models;

use Yii;

/**
 * This is the model class for table "{{%media_image}}".
 *
 * @property int $file_id
 * @property string $extension
 * @property int $width
 * @property int $height
 * @property int $thumb_file_id
 */
class MediaImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'extension'], 'required'],
            [['file_id', 'width', 'height', 'thumb_file_id'], 'integer'],
            [['extension'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => Yii::t('yii2-media', 'File ID'),
            'extension' => Yii::t('yii2-media', 'Extension'),
            'width' => Yii::t('yii2-media', 'Width'),
            'height' => Yii::t('yii2-media', 'Height'),
            'thumb_file_id' => Yii::t('yii2-media', 'Thumb File ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return MediaImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaImageQuery(get_called_class());
    }

    public function extraFields()
    {
        $fields = parent::extraFields();
        $fields['thumb'] = function() {
            if ($this->thumb == null) {
                return null;
            }
            return [
                'id' => $this->thumb->id,
                'fs_path' => $this->thumb->fs_path,
                'fileData' => $this->thumb->fileData,
            ];
        };
        return $fields;
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $expand[] = 'thumb';
        return parent::toArray($fields, $expand, $recursive);
    }

    public function getThumb()
    {
        return $this->hasOne(File::class, ['id' => 'thumb_file_id'])->with('fileData');
    }
}
