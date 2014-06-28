<?php namespace Milkyway\ZenForms\Contracts;
/**
 * Milkyway Multimedia
 * Decorator.php
 *
 * For ease of use, the decorator should have magic methods set on it.
 *
 * @package milkyway-multimedia/silverstripe-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 *
 */
interface Decorator {
    function apply();
    function remove();
    function original();
}