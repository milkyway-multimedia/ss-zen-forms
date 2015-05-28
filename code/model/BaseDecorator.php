<?php namespace Milkyway\SS\ZenForms\Model;

use Milkyway\SS\ZenForms\Contracts\Decorator;

/**
 * Milkyway Multimedia
 * BaseDecorator.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class BaseDecorator extends \ViewableData implements Decorator {
    protected $pullUp;

    public static function decorate() {
        return call_user_func_array([get_called_class(), 'create'], func_get_args());
    }

    public static function create() {
        $args = func_get_args();

        if(!isset($args[0]) || !($args[0] instanceof Decorator))
            return call_user_func_array(['Object', 'create'], array_merge([get_called_class()], $args));

        $item = $args[0];
        $alreadyHasDecorator = false;

        while($item instanceof Decorator) {
            if(get_class($item) === __CLASS__) {
                $alreadyHasDecorator = true;
                break;
            }

            $item = $item->up();
        }

        return $alreadyHasDecorator ? $args[0] : call_user_func_array(['Object', 'create'], array_merge([get_called_class()], $args));
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

	public function debug() {
		$more = $this->up()->hasMethod('debug') ? $this->up()->debug() : 'none';
		return 'Wrapped with ' . get_class($this) . ' - ' . $more;
	}
} 