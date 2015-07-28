<?php namespace Milkyway\SS\ZenForms\Model;

require_once dirname(dirname(__FILE__)) . '/Traits/Decorator.php';

use Milkyway\SS\ZenForms\Contracts\Decorator;
use \Milkyway\SS\ZenForms\Traits\Decorator as CommonMethods;

use ViewableData;

/**
 * Milkyway Multimedia
 * BaseDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class BaseDecorator extends ViewableData implements Decorator {
   use CommonMethods;

    public function __construct($original) {
        $this->pullUp = $original;
        $this->failover = $original;
        parent::__construct($original);
    }
} 