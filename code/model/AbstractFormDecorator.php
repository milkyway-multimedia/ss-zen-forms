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

    protected $pullUp;

    public static function decorate() {
        return call_user_func_array(array(get_called_class(), 'create'), func_get_args());
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

    /**
     * Iterate until we reach the original object
     * A bit hacky but if it works, it works
     *
     * @param SS_HTTPRequest $request
     * @param DataModel      $model
     *
     * @return array|\RequestHandler|\SS_HTTPResponse|string
     */
    public function handleRequest(\SS_HTTPRequest $request, DataModel $model) {
        return $this->original()->handleRequest($request, $model);
    }

    public function __construct($original) {
        $this->pullUp = $original;
        $this->failover = $original;
    }

    public function onlySetIfNotSet($field, $value) {
        $original = $this->original();

        if(!isset($original->$field))
            $original->$field = $value;

        return $this;
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
     * @return HTML
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
     * @return HTML
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
     * @return HTML
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
     * @return HTML
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
} 