<?php namespace Milkyway\ZenForms\Model;
/**
 * Milkyway Multimedia
 * AbstractFormFieldDecorator.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class AbstractFormFieldDecorator extends BaseDecorator {
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
//    public function Field($properties = array()) {
//        $this->original()->setTemplate($this->getTemplates());
//        return $this->original()->Field($properties);
//    }

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
    public function FieldHolder($properties = array()) {
        $obj = ($properties) ? $this->customise($properties) : $this;
        return $obj->renderWith($this->getFieldHolderTemplates());
    }

    /**
     * Returns a restricted field holder used within things like FieldGroups.
     *
     * @param array $properties
     *
     * @return string
     */
    public function SmallFieldHolder($properties = array()) {
        $obj = ($properties) ? $this->customise($properties) : $this;

        return $obj->renderWith($this->getSmallFieldHolderTemplates());
    }
} 