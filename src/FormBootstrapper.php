<?php

/**
 * Milkyway Multimedia
 * FormBootstrapper.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use \Milkyway\SS\ZenForms\Model\AbstractFormDecorator;
use \Milkyway\SS\ZenForms\Contracts\Decorator;

class FormBootstrapper extends AbstractFormDecorator
{
    protected static $disallowed_template_classes = [
        'Object',
        'ViewableData',
        'RequestHandler',
    ];

    public static function get_attributes_from_tag($tag)
    {
        $attributes = [];
        parse_str($tag, $attributes);
        return array_map(
            function ($v) {
                return trim($v, '"');
            }, $attributes);
    }

    public static function get_attributes_for_tag(array $attributes, $exclude = [])
    {
        // Remove empty
        $attributes = array_filter((array)$attributes, function ($v) {
            return ($v || $v === 0 || $v === '0');
        }
        );

        // Remove excluded
        if ($exclude) {
            $attributes = array_diff_key($attributes, array_flip($exclude));
        }

        // Create mark up
        $parts = [];
        foreach ($attributes as $name => $value) {
            $parts[] = ($value === true) ? "{$name}=\"{$name}\"" : "{$name}=\"" . Convert::raw2att($value) . "\"";
        }

        return implode(' ', $parts);
    }

    public static function requirements()
    {
        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(SS_MWM_ZEN_FORMS_DIR . '/js/mwm.zen-forms.js');
    }

    public function getTemplate()
    {
        return $this->suffixTemplates(array_merge((array)$this->up()->getTemplate(), ['Form']));
    }

    public function ajaxify()
    {
        static::requirements();
        $this->addExtraClass('ajax-submit');
        return $this;
    }

    public function __construct($original)
    {
        parent::__construct($original);

        FieldListBootstrapper::decorate($original->Fields());
        FieldListBootstrapper::decorate($original->Actions());
    }
} 