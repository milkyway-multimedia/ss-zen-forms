<?php

/**
 * Milkyway Multimedia
 * CompositeFormFieldBootstrapper.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author  Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

class CompositeFormFieldBootstrapper extends FormFieldBootstrapper
{
    /**
     * This is to deal with an annoying method_exists check in Form
     * @return mixed
     */
    public function FieldList() {
        return $this->up()->FieldList();
    }
}
