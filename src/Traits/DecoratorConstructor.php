<?php namespace Milkyway\SS\ZenForms\Traits;

/**
 * Milkyway Multimedia
 * ViewableDataDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

trait DecoratorConstructor {
    public function __construct($original) {
        $this->pullUp = $original;
        if(isset($this->failover))
            $this->failover = $original;
        parent::__construct($original);
    }
}
