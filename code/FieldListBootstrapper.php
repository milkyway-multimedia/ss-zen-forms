<?php /**
 * Milkyway Multimedia
 * FieldListDecorator.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FieldListBootstrapper extends \Milkyway\ZenForms\Model\BaseDecorator {
    public function apply() {
        $original = $this->original();

        return $original;
    }

    public function remove() {
        $original = $this->original();

        return $original;
    }
} 