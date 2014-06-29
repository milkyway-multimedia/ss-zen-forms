<?php /**
 * Milkyway Multimedia
 * FormFieldBootstrapper.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormFieldBootstrapper extends \Milkyway\ZenForms\Model\BaseDecorator {
    public function apply() {
        $original = $this->original();

        if($original instanceof FormAction
        || $original instanceof FormActionLink) {
            $original->addExtraClass('btn');

            if(!$original->getAttribute('data-loading-text'))
                $original->setAttribute('data-loading-text', _t('LOADING...', 'Loading...'));
        }

        return $original;
    }

    public function remove() {
        $original = $this->original();
        return $original;
    }
} 