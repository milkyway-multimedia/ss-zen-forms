<?php namespace Milkyway\SS\ZenForms\Traits;

/**
 * Milkyway Multimedia
 * ViewableDataDecorator.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use SS_HTTPRequest;
use DataModel;

trait RequestHandlerDecorator {
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
}