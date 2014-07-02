<?php /**
 * Milkyway Multimedia
 * FieldListDecorator.php
 *
 * @package reggardocolaianni.com
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

    public function applyToFields(FieldList $fields) {
        foreach($fields as $field) {
            if($field->isComposite() && $field->hasMethod('FieldList'))
                $this->applyToFields($field->FieldList());
			elseif($field->children && $field->children instanceof $fields)
                $this->applyToFields($field);

            $this->replaceField($field->Name, $item = FormFieldBootstrapper::create($field));
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