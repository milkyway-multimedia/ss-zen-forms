<?php namespace Milkyway\ZenForms\Model;

use Milkyway\ZenForms\Contracts\Decorator;

/**
 * Milkyway Multimedia
 * AbstractFormDecorator.php
 *
 * @todo One of these days some of this will used as a trait
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class AbstractFormDecorator extends \RequestHandler implements Decorator {

    protected $originalItem;

    public static function decorate() {
        $decorator = call_user_func_array(array(get_called_class(), 'create'), func_get_args());
        return $decorator->apply();
    }

    public static function undecorate() {
        $decorator = call_user_func_array(array('Object', 'create'), func_get_args());
        return $decorator->remove();
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

    /**
     * Iterate until we reach the original object
     * A bit hacky but if it works, it works
     *
     * @param SS_HTTPRequest $request
     * @param DataModel      $model
     *
     * @return array|\RequestHandler|\SS_HTTPResponse|string
     */
    public function handleRequest(SS_HTTPRequest $request, DataModel $model) {
        return $this->original()->handleRequest($request, $model);
    }

    public function __construct() {
        $args = func_get_args();

        if(!count($args))
            throw new \LogicException('A decorator requires the original Form passed as the first argument');

        parent::__construct();

        $this->originalItem = $args[0];
        $this->failover = $args[0];
    }

    public function onlySetIfNotSet($field, $value) {
        $original = $this->original();

        if(!isset($original->$field))
            $original->$field = $value;

        return $this;
    }
} 