<?php namespace Milkyway\ZenForms\Model;

use Milkyway\ZenForms\Contracts\Decorator;

/**
 * Milkyway Multimedia
 * BaseDecorator.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class BaseDecorator extends \ViewableData implements Decorator {
    protected $pullUp;

    public static function decorate() {
        return call_user_func_array(array(get_called_class(), 'create'), func_get_args());
    }

    public function __construct($original) {
        $this->pullUp = $original;
        $this->failover = $original;
    }

    public function original() {
        $original = $this->pullUp;

        if($original instanceof Decorator)
            $original = $original->original();

        return $original;
    }

    public function up() {
        return $this->pullUp;
    }

    public function onlySetIfNotSet($field, $value) {
        $original = $this->original();

        if(!isset($original->$field))
            $original->$field = $value;

        return $this;
    }
} 