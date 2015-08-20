<?php namespace Milkyway\SS\ZenForms\Constraints;

/**
 * Milkyway Multimedia
 * RequiredIf.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use FormField;
use Requirements;
use FormBootstrapper;
use ZenValidatorConstraint;

class RequiredIf extends ZenValidatorConstraint {

    /**
     * @var string|FormField
     **/
    protected $targetField;

    /**
     * @var string
     **/
    protected $condition;

    protected $param;


    /**
     * @param string|FormField $field the Name of the field to match
     * @param string $condition The condition to match (as a jQuery selector, after :)
     * @param string $param A parameter for the condition
     **/
    function __construct($field, $condition, $param = null){
        $this->targetField = $field;
        $this->condition = $condition;
        $this->param = $param;

        parent::__construct();
    }

    /**
     * @return FormField
     */
    public function getTargetField() {
        return $this->targetField instanceof \FormField ? $this->targetField : $this->field->getForm()->Fields()->dataFieldByName($this->targetField);
    }

    public function applyParsley(){
        parent::applyParsley();
        $this->removeParsley();

        Requirements::insertHeadTags(sprintf('<script src="%s"></script>', SS_MWM_ZEN_FORMS_DIR . '/js/mwm.zen-forms.top.js'), SS_MWM_ZEN_FORMS_DIR . '/js/mwm.zen-forms.top.js');
        FormBootstrapper::requirements();

        $this->field->setAttribute('data-parsley-validate-if-empty', 'true');

        if($requiredIf = $this->field->getAttribute('data-parsley-requiredif'))
            $this->field->setAttribute('data-parsley-requiredif', $requiredIf . ',' . $this->getTargetFieldWithCondition());
        else
            $this->field->setAttribute('data-parsley-requiredif', $this->getTargetFieldWithCondition());

        $message = $this->getMessage();

        if(!$this->field->getAttribute('data-parsley-requiredif-message'))
            $this->field->setAttribute('data-parsley-requiredif-message', $message);
        else if(strpos($this->field->getAttribute('data-parsley-requiredif-message'), $message) !== false) {
            $this->field->setAttribute('data-parsley-requiredif-message', $this->field->getAttribute('data-parsley-requiredif-message') . ', ' . $message);
        }
    }


    public function removeParsley(){
        parent::removeParsley();

        if($requiredIf = $this->field->getAttribute('data-parsley-requiredif'))
            $this->field->setAttribute('data-parsley-requiredif', trim(str_replace(',,', ',', str_replace($this->getTargetFieldWithCondition(), '', $requiredIf))), ' ,');

        if(!$this->field->getAttribute('data-parsley-requiredif'))
            $this->field->setAttribute('data-parsley-requiredif-message', '');
    }


    function validate($value){
        if(!$this->getTargetField())
            return true;

        switch($this->getNiceCondition()) {
            case 'checked':
            case 'filled':
                if(!$value && $this->getTargetField()->Value())
                    return false;
                break;
            case 'unchecked':
            case 'blank':
                if(!$value && !$this->getTargetField()->Value())
                    return false;
                break;
            case 'in-list':
            case 'in list':
                $param = is_array($this->param) ? $this->param : explode(',', $this->param);
                if(!$value && in_array($this->getTargetField()->Value(), $param))
                    return false;
                break;
            case 'not-in-list':
            case 'not in list':
                $param = is_array($this->param) ? $this->param : explode(',', $this->param);
                if(!$value && !in_array($this->getTargetField()->Value(), $param))
                    return false;
                break;
            case 'has-value':
            case 'has value':
            case 'is-equal-to':
            case 'is equal to':
                if(!$value && $this->getTargetField()->Value() == $this->param)
                    return false;
                break;
            case 'not-value':
            case 'not value':
            case 'is-not-equal-to':
            case 'is not equal to':
                if(!$value && $this->getTargetField()->Value() != $this->param)
                    return false;
                break;
            case 'less-than':
            case 'less than':
                if(!$value && $this->getTargetField()->Value() < $this->param)
                    return false;
                break;
            case 'less-than-or-equal-to':
            case 'less than or equal to':
                if(!$value && $this->getTargetField()->Value() <= $this->param)
                    return false;
                break;
            case 'greater-than':
            case 'greater than':
                if(!$value && $this->getTargetField()->Value() > $this->param)
                    return false;
                break;
            case 'greater-than-or-equal-to':
            case 'greater than or equal to':
                if(!$value && $this->getTargetField()->Value() >= $this->param)
                    return false;
                break;
            case 'starts-with':
            case 'starts with':
                if(!$value && strpos($this->getTargetField()->Value(), $this->param) === 0)
                    return false;
                break;
            case 'ends-with':
            case 'ends with':
                if(!$value && strpos($this->getTargetField()->Value(), $this->param) === 0)
                    return false;
                break;
            case 'between':
                $param = is_array($this->param) ? $this->param : explode('-', $this->param);
                $targetValue = $this->getTargetField()->Value();

                if(!$value && !isset($param[1]) && $targetValue == $param[0])
                    return false;
                else if(!$value && $targetValue >= $param[0] && $targetValue <= $param[1])
                    return false;
                break;
            case 'selected-at-least':
            case 'selected at least':
                if(!$value && count($this->getTargetField()->Value()) >= $this->param)
                    return false;
                break;
            case 'selected-less-than':
            case 'selected less than':
                if(!$value && count($this->getTargetField()->Value()) <= $this->param)
                    return false;
                break;
        }

        return true;
    }


    function getDefaultMessage(){
        $state = $this->getNiceCondition();

        if($this->param) {
            $param = is_array($this->param) ? implode(', ', $this->param) : $this->param;
            $state .= ' ' . $param;
        }

        return _t('ZenValidator.REQUIRED_IF', 'This value is required when \'{target}\' is {state}', [
                'target' => $this->getTargetField() ? $this->getTargetField()->Title() : $this->field,
                'state' => $state,
            ]
        );
    }

    protected function getTargetFieldWithCondition() {
	    $condition = strpos($this->condition, '[') === 0 ? $this->condition : ':' . $this->condition;

        if($this->param && strpos($condition, ':') === 0) {
            $param = is_array($this->param) ? implode(',', $this->param) : $this->param;
            $condition .= '(' . (string) $param . ')';
        }

//        $formField = $this->getTargetField();

//        if($formField->Form)
//            return '#' . $formField->getAttribute('id') . $condition;
//        else
        if($this->getTargetField()) {
            return '[name=' . str_replace(
                ['[', ']'], ['\[', '\]'], $this->getTargetField()->getAttribute('name')
            ) . ']' . $condition;
        }
        else {
            return $this->field . $condition;
        }
    }

    public function getNiceCondition() {
        switch($this->condition) {
            case 'checked':
                return 'checked';
                break;
            case 'not(:checked)':
                return 'unchecked';
                break;
            case 'not-value':
                return 'is not equal to';
                break;
            default:
                return str_replace('-', ' ', $this->condition);
            break;
        }
    }
} 