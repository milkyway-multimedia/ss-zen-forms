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
        if(!$this->decorator) $this->decorator = new FormFieldBootstrapper(new HiddenField('---- Decorator'));
        $this->applyToFields($original);
        return $original;
    }

    public function applyToFields(FieldList $fields) {
        foreach($fields as $field) {
            if($field->isComposite() && $field->hasMethod('FieldList'))
                $this->applyToFields($field->FieldList());
			elseif($field->children && $field->children instanceof $fields)
                $this->applyToFields($field);

            $this->useDecoratorOn($field)->apply();
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

            return $this->useDecoratorOn($field)->remove();
        }
    }

    protected $decorator;

    protected function useDecoratorOn($field) {
        return $this->decorator->setOriginal($field);
    }
} 