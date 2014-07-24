<?php namespace Milkyway\SS\ZenForms\Constraints;
/**
 * Milkyway Multimedia
 * ValidPassword.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class ValidPassword extends \ZenValidatorConstraint {
    /** @var \PasswordValidator  */
    protected $validator = null;

    /** @var float This is only used by the JS plugin - and is more of a guide */
    protected $strengthScaleFactor = 0.4;

    /**
     * @param \PasswordValidator $validator
     * @param float $strengthScaleFactor
     **/
    function __construct($validator, $strengthScaleFactor = 0.4){
        $this->validator = $validator;
        $this->strengthScaleFactor = 0.4;
        parent::__construct();
    }

    public function applyParsley(){
        parent::applyParsley();

        $this->field->setAttribute('data-parsley-validpassword', json_encode(array_merge($this->validator->SettingsForJS, ['strengthScaleFactor' => $this->strengthScaleFactor])));

        $trigger = $this->field->getAttribute('data-parsley-trigger');
        $this->field->setAttribute('data-parsley-trigger', trim($trigger . ' keyup'));

        if(!$this->field->getAttribute('data-parsley-validpassword-message'))
            $this->field->setAttribute('data-parsley-validpassword-message', $this->getMessage());
    }


    public function removeParsley(){
        parent::removeParsley();

        $this->field->setAttribute('data-parsley-validpassword', '');
        $this->field->setAttribute('data-parsley-validpassword-message', '');
        $this->field->setAttribute('data-parsley-trigger', trim(str_replace('keyup', '', $this->field->getAttribute('data-parsley-trigger'))));
    }


    function validate($value){
        if($this->field->Form && $this->field->Form->Record) {
            $result = $this->validator->validate($value, $this->field->Form->Record);
            return $result->valid();
        }

        return true;
    }


    function getDefaultMessage(){
        $settings = $this->validator->SettingsForJS;
        $message = [];
        $label = $this->field->Title();

        if(isset($settings['minimumChars'])) {
            $message[] = _t(
                    'ValidPassword.DESC-PASSWORD_MINIMUM_CHARACTERS',
                    '{field} must be at least {min} characters.',
                    [
                        'field' => $label,
                        'min' => $settings['minimumChars'],
                    ]
                );
        }

        if(isset($settings['minimumScore'])) {
            $message[] = _t(
                'ValidPassword.DESC-PASSWORD_MINIMUM_SCORE',
                '{field} must pass at least {score} of the following requirements',
                [
                    'field' => $label,
                    'score' => $settings['minimumScore'],
                ]
            );
        }

        if (isset($settings['tests']))
        {
            $tests = array();

            if (in_array('lowercase', $settings['tests']))
                $tests[] = _t('ValidPassword.ONE_LOWERCASE_LETTER', 'one lowercase letter');
            if (in_array('uppercase', $settings['tests']))
                $tests[] = _t('ValidPassword.ONE_UPPERCASE_LETTER', 'one uppercase letter');
            if (in_array('digits', $settings['tests']))
                $tests[] = _t('ValidPassword.ONE_NUMBER', 'one number');
            if (in_array('punctuation', $settings['tests']))
                $tests[] = _t('ValidPassword.ONE_SYMBOL', 'one symbol');

            if (count($tests))
            {
                if (count($tests) > 1) {
                    $lastTest = array_pop($tests);
                    $test = implode(', ', $tests) . ' ' . _t('AND', 'and') . ' ' . $lastTest;
                }
                else
                    $test = array_pop($tests);

                $message[] = _t(
                    'ValidPassword.DESC-PASSWORD_REQUIREMENTS',
                    '{field} should have at least {tests}.',
                    [
                        'field' => $label,
                        'tests' => $test,
                    ]
                );
            }
        }

        return implode(' ', $message);
    }
} 