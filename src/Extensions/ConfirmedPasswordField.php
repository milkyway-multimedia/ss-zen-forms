<?php namespace Milkyway\SS\ZenForms\Extensions;

use Extension;
use HiddenField;
use FormFieldBootstrapper;

class ConfirmedPasswordField extends Extension
{

    protected $usePasswordGenerator = false;
    protected $measurePasswordStrength = false;

    public function getLabel()
    {
        if ($title = $this->owner->Title()) {
            return $title;
        } elseif ($field = $this->owner->PasswordField) {
            return $field->Title();
        }

        return '';
    }

    public function getLabelFor()
    {
        if ($field = $this->owner->PasswordField) {
            return $field->ID();
        } else {
            return $this->owner->ID();
        }
    }

    function usePasswordGenerator($do = true)
    {
        $this->usePasswordGenerator = $do;

        return $this->owner;
    }

    function measurePasswordStrength($do = true)
    {
        $this->measurePasswordStrength = $do;
        return $this->owner;
    }

    function PasswordStrengthGuide()
    {
        return $this->measurePasswordStrength;
    }

    public function getVisibleOnClickField()
    {
        $name = $this->owner->getName() . '[_PasswordFieldVisible]';
        $field = $this->owner->children->fieldByName($name);

        // Transforming hidden field to checkbox
        if ($field instanceof \HiddenField) {
            $title = $this->owner->ShowOnClickTitle ? $this->owner->ShowOnClickTitle : _t(
                'ConfirmedPasswordField.CHANGE_YOUR_PASSWORD',
                'Change your password'
            );
            $checkbox = $field->castedCopy('CheckboxField')->setTitle($title)->setForm(
                $this->owner->Form
            )->removeExtraClass('hidden')->addExtraClass('visible-if-trigger');
            $this->owner->children->replaceField($name, $checkbox);
            //$this->owner->setHolderAttribute('data-show-if', '#' . $checkbox->ID() . ':checked');
        } else {
            $checkbox = $field;
        }

        return $checkbox;
    }

    public function getPasswordField()
    {
        return $this->owner->children->fieldByName($this->owner->getName() . '[_Password]')->setForm($this->owner->Form);
    }

    public function getConfirmPasswordField()
    {
        return $this->owner->children->fieldByName($this->owner->Name . '[_ConfirmPassword]')->setForm($this->owner->Form);
    }

    public function onBeforeRenderFieldHolder($decorator)
    {
        if ($decorator instanceof FormFieldBootstrapper) {
            $this->owner->PasswordField->addExtraClass('form-control_password');
            $this->owner->ConfirmPasswordField->addExtraClass('form-control_confirm-password');

            $decorator->removeHolderClass('form-group')->addHolderClass('form-group-holder');

            if ($this->measurePasswordStrength && ($this->owner->PasswordField instanceof FormFieldBootstrapper)) {
                $this->owner->PasswordField->addHolderClass('password-measure--holder')->addExtraClass('password-measure');
            }
        }
    }
}