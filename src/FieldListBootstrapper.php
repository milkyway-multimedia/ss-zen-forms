<?php

/**
 * Milkyway Multimedia
 * FieldListDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use \Milkyway\SS\ZenForms\Model\BaseDecorator;
use \Milkyway\SS\ZenForms\Contracts\Decorator;

class FieldListBootstrapper extends BaseDecorator {
    public function __construct($original) {
        parent::__construct($original);
        $this->apply();
    }

    public function apply() {
        $original = $this->up();
        $this->applyToFields($original);
        //$this->setForm($original);
        return $original;
    }

    public function applyToFields($fields) {
        foreach($fields as $field) {
            if(!$this->canBootstrap($field))
                continue;
            elseif($field->isComposite() && $field->hasMethod('FieldList'))
                $this->applyToFields($field->FieldList());
			elseif(isset($field->children) && $field->children instanceof $fields)
                $this->applyToFields($field->children);

            if((!$field->hasData() || $field->isComposite()) && !($field instanceof FormFieldBootstrapper))
                $fields->replace($field, FormFieldBootstrapper::create($field));
            elseif(!($field instanceof FormFieldBootstrapper))
                $this->replaceField($field->Name, FormFieldBootstrapper::create($field));
        }
    }

    public function remove() {
        $original = $this->original();
        $this->removeFromFields($original);
        return parent::apply();
    }

    public function removeFromFields(FieldList $fields) {
        foreach($fields as $field) {
            if($field->isComposite() && $field->hasMethod('FieldList'))
                $this->removeFromFields($field->FieldList());
            elseif($field->children && $field->children instanceof $fields)
                $this->removeFromFields($field);

            if($field instanceof Decorator)
                $this->replaceField($field->Name, $field->original());
        }
    }

    protected function canBootstrap($field) {
        return !($field instanceof LiteralField || $field instanceof HeaderField);
    }
} 