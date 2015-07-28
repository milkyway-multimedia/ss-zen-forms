<?php

if (!defined('SS_MWM_ZEN_FORMS_DIR'))
    define('SS_MWM_ZEN_FORMS_DIR', basename(rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR)));

if (!defined('SS_MWM_ZEN_FORMS_TRAITS_DIR')) {
    define('SS_MWM_ZEN_FORMS_TRAITS_DIR', implode(DIRECTORY_SEPARATOR, [
        dirname(__FILE__),
        'src',
        'Traits',
    ]));

    define('SS_MWM_ZEN_FORMS_DECORATOR_TRAIT', implode(DIRECTORY_SEPARATOR, [
        SS_MWM_ZEN_FORMS_TRAITS_DIR,
        'Decorator.php'
    ]));
}