<?php

namespace DevGroup\Media\behaviors;

use DevGroup\Media\helpers\AttachmentHelper;
use DevGroup\Media\models\MediaFile;
use DevGroup\Media\models\MediaFileRelation;

use ReflectionClass;
use Yii;
use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;

class MediaFileBehavior extends Behavior
{
    /** @var int */
    public $fileTypeId;

    /** @var string */
    public $relationName;

    protected $values;

    protected $valuesDirty = false;

    public function attach($owner)
    {
        parent::attach($owner);
        $owner->on(ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'updateRelated']);
        $owner->on(ActiveRecord::EVENT_AFTER_INSERT, [$this, 'updateRelated']);
    }

    public function updateRelated()
    {
        if ($this->valuesDirty === false) {
            return true;
        }

        /** @var Model $owner */
        $owner = $this->owner;
        $reflector = new ReflectionClass($owner);
        $classNameHash = $reflector->getShortName();

        MediaFileRelation::deleteAll([
            'model' => $classNameHash,
            'model_id' => $owner->id,
            'relation_name' => $this->relationName
        ]);

        if ($this->values !== '') {
            $values2insert = [];
            $fileIds = explode(',', $this->values);
            foreach ($fileIds as $sort_order => $id) {
                $values2insert[] = [
                    $classNameHash,
                    $owner->id,
                    $this->relationName,
                    $id,
                    $sort_order
                ];
            }
            Yii::$app->db->createCommand()
                ->batchInsert(
                    MediaFileRelation::tableName(),
                    [
                        'model',
                        'model_id',
                        'relation_name',
                        'file_id',
                        'sort_order',
                    ],
                    $values2insert
                )
                ->execute();
        }

        $this->valuesDirty = false;

        return true;
    }

    public function canGetProperty($name, $checkVars = true)
    {
        if ($name === $this->relationName) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        if ($name === $this->relationName) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    public function __get($name)
    {
        if ($name === $this->relationName) {
            if ($this->values === null) {
                AttachmentHelper::retrieveRelated($this->relationName, [$this->owner]);
            }
            return $this->values;
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($name === $this->relationName) {
            if ($this->values !== $value) {
                $this->valuesDirty = true;
            }
            $this->values = $value;
            return;
        }
        parent::__set($name, $value);
    }
}
