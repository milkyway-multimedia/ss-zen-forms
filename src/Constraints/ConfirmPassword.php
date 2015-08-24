<?php namespace Milkyway\SS\ZenForms\Constraints;

/**
 * Milkyway Multimedia
 * ConfirmPassword.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use ZenValidatorConstraint;

class ConfirmPassword extends ZenValidatorConstraint
{
    public $constraints = [];

    public function __construct($constraints) {
        parent::__construct();

        $this->constraints = $constraints;
    }

    public function applyParsley()
    {
        parent::applyParsley();

        if(isset($this->constraints['PasswordField'])) {
            foreach($this->constraints['PasswordField'] as $constraint) {
                $constraint->setField($this->field->PasswordField)->applyParsley();
            }
        }

        if(isset($this->constraints['ConfirmPasswordField'])) {
            foreach($this->constraints['ConfirmPasswordField'] as $constraint) {
                $constraint->setField($this->field->ConfirmPasswordField)->applyParsley();
            }
        }

        $this->field->ConfirmPasswordField->setAttribute('data-parsley-validate-if-empty', 'true');
        $this->field->ConfirmPasswordField->setAttribute('data-parsley-equalto',
            '#' . $this->field->PasswordField->getAttribute('id'));

        if (!$this->field->ConfirmPasswordField->getAttribute('data-parsley-equalto-message')) {
            $this->field->ConfirmPasswordField->setAttribute('data-parsley-equalto-message', $this->getMessage());
        } else {
            if (strpos($this->field->ConfirmPasswordField->getAttribute('data-parsley-requiredif-message'), $this->getMessage()) !== false) {
                $this->field->setAttribute('data-parsley-requiredif-message',
                    $this->field->getAttribute('data-parsley-requiredif-message') . ', ' . $this->getMessage());
            }
        }
    }

    public function removeParsley()
    {
        parent::removeParsley();

        if(isset($this->constraints['PasswordField'])) {
            foreach($this->constraints['PasswordField'] as $constraint) {
                $constraint->setField($this->field->PasswordField)->removeParsley();
            }
        }

        if(isset($this->constraints['ConfirmPasswordField'])) {
            foreach($this->constraints['ConfirmPasswordField'] as $constraint) {
                $constraint->setField($this->field->ConfirmPasswordField)->removeParsley();
            }
        }

        $this->field->ConfirmPasswordField->setAttribute('data-parsley-validate-if-empty', '');
        $this->field->ConfirmPasswordField->setAttribute('data-parsley-equalto', '');
    }

    function validate($value)
    {
        // Other validation is done within the confirmed password field itself (at least for now)
        $currentValue = $this->field->Value();

        if(isset($this->constraints['PasswordField'])) {
            foreach($this->constraints['PasswordField'] as $constraint) {
                $constraint->setField($this->field->PasswordField)->validate($this->field->PasswordField->Value());
            }
        }

        if(isset($this->constraints['ConfirmPasswordField'])) {
            foreach($this->constraints['ConfirmPasswordField'] as $constraint) {
                $constraint->setField($this->field->ConfirmPasswordField)->validate($this->field->ConfirmPasswordField->Value());
            }
        }

        $this->field->setValue($currentValue);

        return $this->field->ConfirmPasswordField->dataValue() == $this->field->PasswordField->dataValue();
    }

    function getDefaultMessage()
    {
        return sprintf(_t('ZenValidator.EQUALTO', 'This value should be the same as the field %s'),
            $this->field->PasswordField->Title());
    }

    public function getConstraintName() {
        return 'equalto';
    }
}