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
    protected $failover;

    protected $_cached = array();

    public static function decorate() {
        $args = func_get_args();
        array_unshift($args, get_called_class());
        $decorator = call_user_func_array(array('Object', 'create'), $args);
        return $decorator->applyCachedToOriginal()->apply();
    }

    public static function undecorate() {
        $args = func_get_args();
        array_shift($args, get_called_class());
        $decorator = call_user_func_array(array('Object', 'create'), $args);
        return $decorator->removeCachedFromOriginal()->remove();
    }

    public function original() {
        $original = $this->originalItem;

        if($original instanceof Decorator)
            $original = $original->original();

        return $original;
    }

    public function setOriginal($original = null) {
        $this->originalItem = $original;
        return $this;
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

        return $this;
    }

    public function removeCachedFromOriginal() {
        if(count($this->_cached)) {
            $original = $this->original();

            foreach($this->_cached as $field => $value)
                $original->$field = null;
        }

        return $this;
    }

    public function __construct() {
        $args = func_get_args();

        if(!count($args))
            throw new \LogicException('A decorator requires the original Form passed as the first argument');

        $this->originalItem = $args[0];
        $this->failover = $args[0];
    }
} 