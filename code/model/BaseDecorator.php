<?php namespace Milkyway\ZenForms\Model;

use Milkyway\ZenForms\Contracts\Decorator;

/**
 * Milkyway Multimedia
 * BaseDecorator.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class BaseDecorator implements Decorator {
    protected $originalItem;

    protected $_cached = array();

    public static function decorate() {
        $decorator = call_user_func_array(array('Object', 'create'), func_get_args());
        return $decorator->applyCachedToOriginal()->apply();
    }

    public function original() {
        $original = $this->originalItem;

        if($original instanceof Decorator)
            $original = $original->original();

        return $original;
    }

    public function onlySetIfNotSet($field, $value) {
        if(!isset($this->original()->$field))
            $this->_cached[$field] = $value;

        return $this;
    }

    public function applyCachedToOriginal() {
        if(count($this->_cached)) {
            $original = $this->original();

            foreach($this->_cached as $field => $value)
                $original->$field = $value;
        }
    }
} 