<?php namespace Milkyway\SS\ZenForms\Overrides;
/**
 * Milkyway Multimedia
 * PasswordValidator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use PasswordValidator as Original;

class PasswordValidator extends Original {
    public function getSettingsForJS() {
        return array(
            'minimumChars' => $this->minLength,
            'minimumScore' => $this->minScore,
            'tests' => $this->testNames,
        );
    }
} 