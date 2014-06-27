<?php namespace Milkyway\ZenForms\Model;

use Milkyway\ZenForms\Contracts\Decorator;

/**
 * Milkyway Multimedia
 * AbstractFormDecorator.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class AbstractFormDecorator extends \RequestHandler implements Decorator {
    protected $original;

    public static function decorate() {
        return call_user_func_array('create', func_get_args());
    }

    public function __construct() {
        $args = func_get_args();

        if(!count($args))
            throw new \LogicException('A decorator requires the original object passed as the first argument');

        parent::__construct();

        $this->original = $args[0];
        $this->failover = $args[0];
    }
} 