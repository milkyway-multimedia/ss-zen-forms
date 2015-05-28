<?php /**
 * Milkyway Multimedia
 * FormBootstrapper.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormBootstrapper extends \Milkyway\SS\ZenForms\Model\AbstractFormDecorator {
    public $template = 'Form_bootstrapped';

    public static function reset_template_for(\Milkyway\SS\ZenForms\Contracts\Decorator $item, $templateName) {
        $originalTemplates = $item->up()->getTemplate();

        if(is_array($originalTemplates)) {
            $template = array_pop($originalTemplates);
        }
        else {
            $template = $originalTemplates;
            $originalTemplates = null;
        }

        $templates = array();

        if($template && !in_array($template, array(get_class($item->original()), 'Form')))
            $templates[] = $template;

        if(is_string($templateName))
            $templates[] = $templateName;
        else
            $templates = array_merge($templates, $templateName);

        if($originalTemplates && count($originalTemplates)) {
            $templates = array_merge($templates, $originalTemplates);
        }

        $templates[] = $item->class;
        $templates[] = 'Form';

        $templates = array_filter(array_unique($templates));

        return $templates;
    }

    public static function get_attributes_from_tag($tag) {
        $attributes = array();
        parse_str($tag, $attributes);
        return array_map(
            function($v) {
                return trim($v, '"');
            }, $attributes);
    }

    public static function get_attributes_for_tag(array $attributes, $exclude = array()) {
        // Remove empty
        $attributes = array_filter((array)$attributes, function($v) {
                return ($v || $v === 0 || $v === '0');
            }
        );

        // Remove excluded
        if($exclude)
            $attributes = array_diff_key($attributes, array_flip($exclude));

        // Create mark up
        $parts = array();
        foreach($attributes as $name => $value) {
            $parts[] = ($value === true) ? "{$name}=\"{$name}\"" : "{$name}=\"" . Convert::raw2att($value) . "\"";
        }

        return implode(' ', $parts);
    }

    public function getTemplate() {
        return FormBootstrapper::reset_template_for($this, $this->template);
    }

    public function ajaxify() {
        $this->addExtraClass('ajax-submit');
        return $this;
    }

    public function addExtraClass($classes) {
        $this->up()->addExtraClass($classes);
        return $this;
    }

    public function __construct($original) {
        parent::__construct($original);

        FieldListBootstrapper::decorate($original->Fields());
        FieldListBootstrapper::decorate($original->Actions());
    }
} 