<?php namespace Milkyway\SS\ZenForms\Constraints;

/**
 * Milkyway Multimedia
 * RequiredIf.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use ZenValidatorConstraint;

use FormField;

class Multiple extends ZenValidatorConstraint
{
    public $strict = false;
    /**
     * @var array
     **/
    protected $constraints = [];

    /**
     * @param array $constraints The RequiredIf constraints to check for
     **/
    function __construct($constraints)
    {
        $this->constraints = $constraints;
        parent::__construct();
    }

    public function setField(FormField $field) {
        if($field->Form) {
            foreach ($this->constraints as $fieldName => $constraints) {
                foreach((array)$constraints as $constraint) {
                    if($constraint->getField()) continue;
                    $constraint->setField($field->Form->Fields()->dataFieldByName($fieldName));
                }
            }
        }

        return parent::setField($field);
    }

    public function applyParsley()
    {
        parent::applyParsley();

        foreach ($this->constraints as $constraints) {
            foreach((array)$constraints as $constraint) {
                $constraint->applyParsley();
            }
        }
    }

    public function removeParsley()
    {
        parent::removeParsley();

        foreach ($this->constraints as $constraints) {
            foreach((array)$constraints as $constraint) {
                $constraint->removeParsley();
            }
        }
    }


    function validate($value)
    {
        $valid = 0;
        $totalConstraints = 0;

        foreach ($this->constraints as $constraints) {
            foreach((array)$constraints as $constraint) {
                $totalConstraints++;

                if($this->strict && !$constraint->validate($value)) {
                    $this->setMessage($constraint->getMessage());
                    return false;
                }
                elseif($constraint->validate($value)) {
                    $valid++;
                }
            }
        }

        return !$totalConstraints || $valid > 0;
    }


    function getDefaultMessage()
    {
        $messages = [];

        foreach($this->constraints as $constraint) {
            $messages[] = $constraint->getDefaultMessage();
        }

        return implode('; ', $messages);
    }

    public function getConstraintName() {
        return '';
    }
} 