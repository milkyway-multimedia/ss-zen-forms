<?php namespace Milkyway\SS\ZenForms\Traits;

/**
 * Milkyway Multimedia
 * ViewableDataDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use ViewableData;

trait ViewableDataDecorator {
    public function obj($fieldName, $arguments = null, $forceReturnedObject = true, $cache = false, $cacheName = null) {
        $value = parent::obj($fieldName, $arguments, $forceReturnedObject, $cache, $cacheName);

        if(!$value || (is_object($value) && ($value instanceof ViewableData) && !$value->exists())) {
            return $this->up()->obj($fieldName, $arguments, $forceReturnedObject, $cache, $cacheName);
        }
        else {
            return $value;
        }
    }

    public function hasMethod($method) {
        return parent::hasMethod($method) || $this->up()->hasMethod($method);
    }

    public function __call($method, $arguments) {
        $return = call_user_func_array(array($this->pullUp, $method), $arguments);

        if($return === $this->original()) {
            // Trying to support chain commands
            return $this;
        }

        return $return;
    }
}