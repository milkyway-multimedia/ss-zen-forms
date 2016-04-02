<?php namespace Milkyway\SS\ZenForms\Model;

/**
 * Milkyway Multimedia
 * AbstractFormFieldDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

require_once dirname(dirname(__FILE__)) . '/Traits/Decorator.php';
require_once dirname(dirname(__FILE__)) . '/Traits/DecoratorConstructor.php';
require_once dirname(dirname(__FILE__)) . '/Traits/ViewableDataDecorator.php';
require_once dirname(dirname(__FILE__)) . '/Traits/RequestHandlerDecorator.php';

use Milkyway\SS\ZenForms\Traits\Decorator as CommonMethods;
use Milkyway\SS\ZenForms\Traits\DecoratorConstructor as Constructor;
use Milkyway\SS\ZenForms\Traits\ViewableDataDecorator as ViewableDataDecorator;
use Milkyway\SS\ZenForms\Traits\RequestHandlerDecorator as RequestHandlerDecorator;

use RequestHandler;

use ReflectionClass;

abstract class AbstractFormFieldDecorator extends RequestHandler
{
    use Constructor, CommonMethods, ViewableDataDecorator, RequestHandlerDecorator {
        Constructor::__construct as private __decorate;
    }

    protected $customisations = [];

    public function __construct($original)
    {
        parent::__construct();
        $this->__decorate($original);
    }

    /**
     * The following methods override the render of the field, to use the methods
     * of the decorator(s) rather than the field methods when required
     */

    /**
     * Returns the form field - used by templates.
     * Although FieldHolder is generally what is inserted into templates, all of the field holder
     * templates make use of $Field.  It's expected that FieldHolder will give you the "complete"
     * representation of the field on the form, whereas Field will give you the core editing widget,
     * such as an input tag.
     *
     * @param array $properties key value pairs of template variables
     * @return string
     */
    public function Field($properties = [])
    {
        $obj = ($properties) ? $this->customise($properties) : $this;

        $class = new ReflectionClass($this->original());
        $method = $class->getMethod(__FUNCTION__);

        if ($method->class != 'FormField') {
            if (!$this->original()->Template) {
                $template = $this->getTemplates();
                $this->original()->Template = array_shift($template);
            }

            $customisations = [];

            foreach ($this->customisations as $customisation) {
                $customisations[$customisation] = $this->$customisation();
            }

            return $this->original()->customise($customisations)->Field($properties);
        } else {
            return $obj->renderWith($this->getTemplates());
        }
    }

    /**
     * Returns a "field holder" for this field - used by templates.
     *
     * Forms are constructed by concatenating a number of these field holders.
     * The default field holder is a label and a form field inside a div.
     * @see FieldHolder.ss
     *
     * @param array $properties key value pairs of template variables
     * @return string
     */
    public function FieldHolder($properties = [])
    {
        $obj = ($properties) ? $this->customise($properties) : $this;

        $class = new ReflectionClass($this->original());
        $method = $class->getMethod(__FUNCTION__);

        $this->original()->extend('onBeforeRenderFieldHolder', $this, $properties);

        if ($method->class != 'FormField') {
            if (!$this->original()->FieldHolderTemplate) {
                $template = $this->getFieldHolderTemplates();
                $this->original()->FieldHolderTemplate = array_shift($template);
            }

            $customisations = [];

            foreach ($this->customisations as $customisation) {
                $customisations[$customisation] = $this->$customisation();
            }

            return $this->original()->customise($customisations)->FieldHolder($properties);
        } else {
            return $obj->renderWith($this->getFieldHolderTemplates());
        }
    }

    /**
     * Returns a restricted field holder used within things like FieldGroups.
     *
     * @param array $properties
     *
     * @return string
     */
    public function SmallFieldHolder($properties = [])
    {
        $obj = ($properties) ? $this->customise($properties) : $this;

        $class = new ReflectionClass($this->original());
        $method = $class->getMethod(__FUNCTION__);

        $this->original()->extend('onBeforeRenderSmallFieldHolder', $this, $properties);

        if ($method->class != 'FormField') {
            if (!$this->original()->SmallFieldHolderTemplate) {
                $template = $this->getSmallFieldHolderTemplates();
                $this->original()->SmallFieldHolderTemplate = array_shift($template);
            }

            $customisations = [];

            foreach ($this->customisations as $customisation) {
                $customisations[$customisation] = $this->$customisation();
            }

            return $this->original()->customise($customisations)->SmallFieldHolder($properties);
        } else {
            return $obj->renderWith($this->getSmallFieldHolderTemplates());
        }
    }

    public function forTemplate()
    {
        return $this->Field();
    }
}
