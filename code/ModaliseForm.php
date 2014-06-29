<?php
/**
 * Milkyway Multimedia
 * ModaliseForm.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class ModaliseForm extends FormBootstrapper {
    public $template = array('Form_Modal', 'Form_Bootstrapped');

    public function __construct($original, $title = '') {
        parent::__construct($original);
        $original->FormModalTitle = $title;
    }
} 