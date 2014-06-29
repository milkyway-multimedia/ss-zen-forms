<?php /**
 * Milkyway Multimedia
 * FormBootstrapper.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormBootstrapper extends \Milkyway\ZenForms\Model\AbstractFormDecorator {
    public $template = 'Form_bootstrapped';

    public static function reset_template_for(\Milkyway\ZenForms\Contracts\Decorator $item, $templateName) {
        $originalTemplates = $item->original()->getTemplate();

        if(is_array($originalTemplates)) {
            $template = array_pop($originalTemplates);
        }
        else {
            $template = $originalTemplates;
            $originalTemplates = null;
        }

        $templates = array();

        if($template && !in_array($template, array($item->class, 'Form')))
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

        $templates = array_unique($templates);

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

    public function apply() {
        $original = $this->original();

        $this->onlySetIfNotSet('FormModalID', $original->FormName() . '-Modal');

        FieldListBootstrapper::decorate($original->Fields());
        FieldListBootstrapper::decorate($original->Actions());

        return $original->setTemplate($this->getTemplate());
    }

    public function remove() {
        $original = $this->original();

        $original->FormModalID = null;

        FieldListBootstrapper::undecorate($original->Fields());
        FieldListBootstrapper::undecorate($original->Actions());

        return $original->setTemplate(null);
    }
} 