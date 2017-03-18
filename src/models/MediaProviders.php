<?php

namespace DevGroup\Media\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%media_providers}}".
 *
 * @property int $id
 * @property string $class_name
 * @property resource $options
 * @property string $url_provider_class_name
 * @property resource $url_provider_options
 * @property int $tree_id
 */
class MediaProviders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_providers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name', 'url_provider_class_name'], 'required'],
            [['options', 'url_provider_options',], 'safe'],
            [['options', 'url_provider_options'], 'default', 'value' => []],
            [['tree_id'], 'integer'],
            ['tree_id', 'default', 'value' => 0],
            [['class_name', 'url_provider_class_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii2-media', 'ID'),
            'class_name' => Yii::t('yii2-media', 'Handler'),
            'options' => Yii::t('yii2-media', 'Handler Options'),
            'tree_id' => Yii::t('yii2-media', 'Tree ID'),
            'url_provider_class_name' => Yii::t('yii2-media', 'URL Provider'),
            'url_provider_options' => Yii::t('yii2-media', 'URL Provider Options'),
        ];
    }

    /**
     * @inheritdoc
     * @return MediaProvidersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaProvidersQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        $this->options = Json::encode($this->options);
        $this->url_provider_options = Json::encode($this->url_provider_options);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->options = Json::decode($this->options);
        $this->url_provider_options = Json::decode($this->url_provider_options);
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->options = Json::decode($this->options);
        $this->url_provider_options = Json::decode($this->url_provider_options);
    }
}
