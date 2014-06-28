<?php
/**
 * Milkyway Multimedia
 * ModaliseForm.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class ModaliseForm extends FormBootstrapper {
    public function __construct($original, $title = '') {
        parent::__construct($original);
        $original->FormModalTitle = $title;
    }

    public function getTemplate() {
        return FormBootstrapper::reset_template_for($this, 'Form_Modal');
    }
} 