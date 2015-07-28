<?php namespace Milkyway\SS\ZenForms\Extensions;

use Extension;
use Member;
use HiddenField;

class ConfirmedPasswordField extends Extension {

    protected $usePasswordGenerator = false;
    protected $measurePasswordStrength = true;
    protected $measurePasswordValidator = null;

    public function getLabel()
    {
        if ($title = $this->owner->Title())
            return $title;
        elseif ($field = $this->owner->PasswordField)
        {
            return $field->Title();
        }

        return '';
    }

    public function getLabelFor()
    {
        if ($field = $this->owner->PasswordField)
            return $field->ID();
        else
            return $this->owner->ID();
    }

    public function addPasswordStrengthHelper($text = '')
    {
        $this->owner->PasswordStrengthHelper = $text ? $text : _t(
            'ConfirmedPasswordField.PASSWORD_STRENGTH_HELPER',
            '[strength]'
        );

        return $this->owner;
    }

    function usePasswordGenerator($do = true)
    {
        $this->usePasswordGenerator = $do;

        return $this->owner;
    }

    function measurePasswordStrength($do = true, $validator = null)
    {
        $this->measurePasswordStrength  = $do;
        $this->measurePasswordValidator = $validator;

        return $this->owner;
    }

    function PasswordStrengthGuide()
    {
        return $this->measurePasswordStrength;
    }

    public function getVisibleOnClickField()
    {
        $name  = $this->owner->getName() . '[_PasswordFieldVisible]';
        $field = $this->owner->children->fieldByName($name);

        // Transforming hidden field to checkbox
        if ($field instanceof \HiddenField)
        {
            $title    = $this->owner->ShowOnClickTitle ? $this->owner->ShowOnClickTitle : _t(
                'ConfirmedPasswordField.CHANGE_YOUR_PASSWORD',
                'Change your password'
            );
            $checkbox = $field->castedCopy('CheckboxField')->setTitle($title)->setForm(
                $this->owner->Form
            )->removeExtraClass('hidden')->addExtraClass('visible-if-trigger');
            $this->owner->children->replaceField($name, $checkbox);
            //$this->owner->setHolderAttribute('data-show-if', '#' . $checkbox->ID() . ':checked');
        } else
            $checkbox = $field;

        return $checkbox;
    }

    public function getPasswordField()
    {
        $field = $this->owner->children->fieldByName($this->owner->getName() . '[_Password]');
        $field->addExtraClass('form-control_password password-measure');

        return $field;
    }

    public function getConfirmPasswordField()
    {
        $field = $this->owner->children->fieldByName($this->owner->Name . '[_ConfirmPassword]');
        $field->addExtraClass('form-control_confirm-password');
        return $field;
    }
}