<?php namespace Milkyway\SS\ZenForms\Contracts;
/**
 * Milkyway Multimedia
 * Decorator.php
 *
 * For ease of use, the decorator should have magic methods set on it.
 * See the Traits included for some ideas on magic methods.
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 *
 */
interface Decorator {
    function original();
    function up();
}