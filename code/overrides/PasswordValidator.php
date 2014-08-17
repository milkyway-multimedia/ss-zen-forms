<?php namespace Milkyway\SS\ZenForms\Overrides;
/**
 * Milkyway Multimedia
 * PasswordValidator.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class PasswordValidator extends \PasswordValidator {
    public function getSettingsForJS() {
        return array(
            'minimumChars' => $this->minLength,
            'minimumScore' => $this->minScore,
            'tests' => $this->testNames,
        );
    }
} 