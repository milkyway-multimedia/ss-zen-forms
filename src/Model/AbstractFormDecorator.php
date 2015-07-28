<?php namespace Milkyway\SS\ZenForms\Model;

require_once dirname(dirname(__FILE__)) . '/Traits/Decorator.php';

use Milkyway\SS\ZenForms\Contracts\Decorator;
use \Milkyway\SS\ZenForms\Traits\Decorator as CommonMethods;

use SSViewer;
use RequestHandler;
use SS_HTTPRequest;
use DataModel;

/**
 * Milkyway Multimedia
 * AbstractFormDecorator.php
 *
 * @todo One of these days some of this will used as a trait
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class AbstractFormDecorator extends RequestHandler implements Decorator {
    use CommonMethods;

    public function __construct($original) {
        $this->pullUp = $original;
        $this->failover = $original;
        parent::__construct();
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

    /**
     * The following methods override the render of the form, to use the methods
     * of the decorator(s) rather than the form methods when required
     */

    /** Return a rendered version of this form.
     *
     * This is returned when you access a form as $FormObject rather
     * than <% with FormObject %>
     *
     * @return string
     */
    public function forTemplate() {
        $return = $this->renderWith(array_merge(
                (array)$this->getTemplate(),
                array('Form')
            ));

        // Now that we're rendered, clear message
        $this->clearMessage();

        return $return;
    }

    /**
     * Return a rendered version of this form, suitable for ajax post-back.
     *
     * It triggers slightly different behaviour, such as disabling the rewriting
     * of # links.
     *
     * @return string
     */
    public function forAjaxTemplate() {
        $view = new SSViewer(array(
            $this->getTemplate(),
            'Form'
        ));

        $return = $view->dontRewriteHashlinks()->process($this);

        // Now that we're rendered, clear message
        $this->clearMessage();

        return $return;
    }

    /**
     * Returns an HTML rendition of this form, without the <form> tag itself.
     *
     * Attaches 3 extra hidden files, _form_action, _form_name, _form_method,
     * and _form_enctype.  These are the attributes of the form.  These fields
     * can be used to send the form to Ajax.
     *
     * @return string
     */
    public function formHtmlContent() {
        $this->IncludeFormTag = false;
        $content = $this->forTemplate();
        $this->IncludeFormTag = true;

        $content .= "<input type=\"hidden\" name=\"_form_action\" id=\"" . $this->FormName . "_form_action\""
                    . " value=\"" . $this->FormAction() . "\" />\n";
        $content .= "<input type=\"hidden\" name=\"_form_name\" value=\"" . $this->FormName() . "\" />\n";
        $content .= "<input type=\"hidden\" name=\"_form_method\" value=\"" . $this->FormMethod() . "\" />\n";
        $content .= "<input type=\"hidden\" name=\"_form_enctype\" value=\"" . $this->getEncType() . "\" />\n";

        return $content;
    }

    /**
     * Render this form using the given template, and return the result as a
     * string.
     *
     * You can pass either an SSViewer or a template name.
     *
     * @param SSViewer|string $template
     *
     * @return string
     */
    public function renderWithoutActionButton($template) {
        $custom = $this->customise(array(
                "Actions" => "",
            ));

        if(is_string($template)) {
            $template = new SSViewer($template);
        }

        return $template->process($custom);
    }

    public function __toString() {
        return is_object($this->forTemplate()) ? $this->forTemplate()->Value : $this->forTemplate();
    }

    public function hasMethod($method) {
        return $this->up()->hasMethod($method) || parent::hasMethod($method);
    }
} 