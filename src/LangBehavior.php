<?php
namespace boryshaiduchuk\langbehavior;

use yii\db\ActiveRecord;

/**
 * Behavior translate columns from main model and save in auxiliary
 */
class LangBehavior extends \yii\base\Behavior
{
    /**
     * Model for translation
     * @var ActiveRecord
     */
    public $t;

    /**
     * Name key of the translation model
     * @var string
     */
    public $fk;

    /**
     * Language id
     * @var int
     */
    public $l;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'populateAttributes',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
        $this->saveTranslation();

        return true;
    }

    /**
     * Assign data to Model from parent model
     */
    public function populateAttributes()
    {
        $this->t->setAttributes($this->owner->attributes, false);
        $this->t->lang_id = $this->l;

        foreach ($this->t->attributes as $attr => $val) {
            if ($this->owner->hasProperty($attr) || $this->owner->hasAttribute($attr)) {
                $this->t->{$attr} = $this->owner->{$attr};
            }
        }

        if (!$this->t->validate()) {
            foreach ($this->t->errors as $k => $v) {
                $this->owner->addError($k, $v[0]);
            }
        }
    }

    /**
     * Save translate model
     */
    public function saveTranslation()
    {
        $this->t->{$this->fk} = $this->owner->primaryKey;

        if ($this->t->validate()) {
            $this->t->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->initTranslation();
    }

    /**
     * @param boolean $anyTranslation
     */
    public function initTranslation($anyTranslation = false)
    {
        if ($model = $this->getTranslates($anyTranslation)->one()) {
            $this->t = $model;
            if ($this->t->isNewRecord) {
                return $model;
            }
            foreach ($this->t->attributes as $attr => $val) {
                if ($attr == 'order') {
                    continue;
                }
                if ($this->owner->hasProperty($attr)) {
                    $this->owner->{$attr} = $val;
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslates($anyTranslation = false)
    {
        $condition = [$this->fk => $this->owner->primaryKey];
        if (!$anyTranslation)
            $condition['lang_id'] = $this->l;
        $this->t->{$this->fk} = $this->owner->primaryKey;
        $q = $this->t->find();
        $q->where($condition);
        return $q;
    }

}