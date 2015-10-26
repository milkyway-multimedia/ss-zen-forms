<?php

/**
 * Milkyway Multimedia
 * ModaliseForm.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

class ModaliseForm extends FormBootstrapper
{
    public $title;

    public function __construct($original, $title = '')
    {
        parent::__construct($original);
        $this->title = $title;
        $this->templateSuffix = '_Modal';
    }

    public function getFormModalTitle()
    {
        return $this->title;
    }

    function getFormModalID()
    {
        return $this->FormName() . '-Modal';
    }
} 