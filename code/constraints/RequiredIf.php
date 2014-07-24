<?php namespace Milkyway\SS\ZenForms\Constraints;
/**
 * Milkyway Multimedia
 * Constraint_RequiredIf.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class RequiredIf extends \ZenValidatorConstraint {

    /**
     * @var string
     **/
    protected $targetField;

    /**
     * @var string
     **/
    protected $condition;


    /**
     * @param string $field the Name of the field to match
     * @param string $condition The condition to match (as a jQuery selector, after :)
     **/
    function __construct($field, $condition){
        $this->targetField = $field;
        $this->condition = $condition;
        parent::__construct();
    }

    /**
     * @return FormField
     */
    public function getTargetField() {
        return $this->field->getForm()->Fields()->dataFieldByName($this->targetField);
    }

    public function applyParsley(){
        parent::applyParsley();

        $this->field->setAttribute('data-parsley-validate-if-empty', 'true');

        if($requiredIf = $this->field->getAttribute('data-parsley-requiredif'))
            $this->field->setAttribute('data-parsley-requiredif', $requiredIf . ',' . $this->getFieldWithCondition());
        else
            $this->field->setAttribute('data-parsley-requiredif', $this->getFieldWithCondition());

        if(!$this->field->getAttribute('data-parsley-requiredif-message'))
            $this->field->setAttribute('data-parsley-requiredif-message', $this->getMessage());
    }


    public function removeParsley(){
        parent::removeParsley();

        if($requiredIf = $this->field->getAttribute('data-parsley-requiredif'))
            $this->field->setAttribute('data-parsley-requiredif', trim(str_replace(',,', ',', str_replace($this->getFieldWithCondition(), '', $requiredIf))), ' ,');

        if(!$this->field->getAttribute('data-parsley-requiredif'))
            $this->field->setAttribute('data-parsley-requiredif-message', '');
    }


    function validate($value){
        switch($this->getNiceCondition()) {
            case 'checked':
                if(!$value && $this->getTargetField()->Value())
                    return false;
                break;
            case 'unchecked':
                if(!$value && !$this->getTargetField()->Value())
                    return false;
                break;
        }
        return true;
    }


    function getDefaultMessage(){
        return _t('ZenValidator.REQUIRED_IF', 'This value is required when \'{target}\' is {state}', [
                'target' => $this->getTargetField()->Title(),
                'state' => $this->getNiceCondition(),
            ]
        );
    }

    protected function getFieldWithCondition() {
        return '#' . $this->getTargetField()->getAttribute('id') . ':' . $this->condition;
    }

    public function getNiceCondition() {
        switch($this->condition) {
            case 'checked':
                return 'checked';
                break;
            case 'not(:checked)':
                return 'unchecked';
                break;
            default:
                return 'unknown';
            break;
        }
    }
} 