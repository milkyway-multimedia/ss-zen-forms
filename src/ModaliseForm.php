<?php
/**
 * Milkyway Multimedia
 * ModaliseForm.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class ModaliseForm extends FormBootstrapper {
    public $template = array('Form_Modal', 'Form_Bootstrapped');

    public $title;

    public function __construct($original, $title = '') {
        parent::__construct($original);
        $this->title = $title;
    }

    public function getFormModalTitle() {
        return $this->title;
    }

    function getFormModalID() {
        return $this->FormName() . '-Modal';
    }
} 