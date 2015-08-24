<?php namespace Milkyway\SS\ZenForms\Constraints;

/**
 * Milkyway Multimedia
 * RequiredIfStrict.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

class RequiredIfStrict extends RequiredIf
{
    public function getConstraintName() {
        return 'requiredifstrict';
    }
} 