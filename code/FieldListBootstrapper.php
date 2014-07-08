<?php /**
 * Milkyway Multimedia
 * FieldListDecorator.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FieldListBootstrapper extends \Milkyway\ZenForms\Model\BaseDecorator {
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
            if($field instanceof LiteralField || $field instanceof HeaderField)
                continue;
            elseif($field->isComposite() && $field->hasMethod('FieldList'))
                $this->applyToFields($field->FieldList());
			elseif(isset($field->children) && $field->children instanceof $fields)
                $this->applyToFields($field->children);

            if(!$field->hasData() || $field->isComposite())
                $fields->replace($field, FormFieldBootstrapper::create($field));
            else
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

            if($field instanceof \Milkyway\ZenForms\Contracts\Decorator)
                $this->replaceField($field->Name, $field->original());
        }
    }
} 