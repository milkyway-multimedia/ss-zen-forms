<?php namespace Milkyway\SS\ZenForms\Traits;

/**
 * Milkyway Multimedia
 * Decorator.php
 *
 * @package milkywaymultimedia.com.au
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use Milkyway\SS\ZenForms\Contracts\Decorator as Contract;

trait Decorator {
    protected $pullUp;

    public static function decorate() {
        return call_user_func_array([get_called_class(), 'create'], func_get_args());
    }

    public static function create() {
        $args = func_get_args();

        if(!isset($args[0]) || !($args[0] instanceof Contract))
            return call_user_func_array(['Object', 'create'], array_merge([get_called_class()], $args));

        $item = $args[0];
        $alreadyHasDecorator = false;

        while($item instanceof Contract) {
            if(get_class($item) === __CLASS__) {
                $alreadyHasDecorator = true;
                break;
            }

            $item = $item->up();
        }

        return $alreadyHasDecorator ? $args[0] : call_user_func_array(['Object', 'create'], array_merge([get_called_class()], $args));
    }

    public function original() {
        $original = $this->pullUp;

        if($original instanceof Contract)
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

    public function debug() {
        $more = $this->up()->hasMethod('debug') ? $this->up()->debug() : 'none';
        return 'Wrapped with ' . get_class($this) . ' - ' . $more;
    }
}