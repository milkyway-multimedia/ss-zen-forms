<?php /**
 * Milkyway Multimedia
 * FormBootstrapper.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormBootstrapper extends \Milkyway\ZenForms\Model\AbstractFormDecorator {
    public $template = 'Form_Bootstrapped';

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

        if($template && $template != $item->class)
            $templates[] = $template;

        if(is_string($templateName))
            $templates[] = $templateName;
        else
            $templates = array_merge($templates, $templateName);

        if($originalTemplates && count($originalTemplates)) {
            $templates = array_merge($templates, $originalTemplates);
        }

        $templates[] = $item->class;

        return $templates;
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