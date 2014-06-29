<?php /**
 * Milkyway Multimedia
 * FieldListDecorator.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FieldListBootstrapper extends \Milkyway\ZenForms\Model\BaseDecorator {
    public function apply() {
        $original = $this->original();
        return $this->applyToFields($original);
    }

    public function applyToFields(FieldList $fields) {
        foreach($fields as $field) {
            if($field->isComposite() && $field->hasMethod('FieldList'))
                $this->applyToFields($field->FieldList());
			elseif($field->children && $field->children instanceof $fields)
                $this->applyToFields($field);

            return $this->decorator($field)->apply();
        }

        return $fields;
    }

    public function remove() {
        $original = $this->original();
        return $this->removeFromFields($original);
    }

    public function removeFromFields(FieldList $fields) {
        foreach($fields as $field) {
            if($field->isComposite() && $field->hasMethod('FieldList'))
                $this->removeFromFields($field->FieldList());
            elseif($field->children && $field->children instanceof $fields)
                $this->removeFromFields($field);

            return $this->decorator($field)->remove();
        }

        return $fields;
    }

    protected $decorator;

    protected function decorator($field) {
        if(!$this->decorator)
            $this->decorator = new FormFieldBootstrapper($field);

        return $this->decorator->setOriginal($field);
    }
} 