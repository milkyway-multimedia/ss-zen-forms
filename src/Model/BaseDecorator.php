<?php namespace Milkyway\SS\ZenForms\Model;

require_once dirname(dirname(__FILE__)) . '/Traits/Decorator.php';
require_once dirname(dirname(__FILE__)) . '/Traits/DecoratorConstructor.php';
require_once dirname(dirname(__FILE__)) . '/Traits/ViewableDataDecorator.php';

use Milkyway\SS\ZenForms\Contracts\Decorator;
use Milkyway\SS\ZenForms\Traits\Decorator as CommonMethods;
use Milkyway\SS\ZenForms\Traits\DecoratorConstructor as Constructor;
use Milkyway\SS\ZenForms\Traits\ViewableDataDecorator as ViewableDataDecorator;

use ViewableData;

/**
 * Milkyway Multimedia
 * BaseDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class BaseDecorator extends ViewableData implements Decorator
{
    use Constructor, CommonMethods, ViewableDataDecorator {
        Constructor::__construct as private __decorate;
    }

    public function __construct($original) {
        parent::__construct();
        $this->__decorate($original);
    }
} 