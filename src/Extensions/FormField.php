<?php
/**
 * Milkyway Multimedia
 * FormField.php
 *
 * @package relatewell.org.au
 * @author  Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

namespace Milkyway\SS\ZenForms\Extensions;

use Extension;
use SS_HTTPResponse;

class FormField extends Extension
{
    private static $allowed_actions = [
        'verify',
    ];

    protected $verifyCallback;

    public function useCallbackForVerification($callback = null)
    {
        $this->verifyCallback = $callback;

        return $this->owner;
    }

    public function verify($r = null)
    {
        $result = true;

        if ($this->owner->hasMethod('beforeVerification')) {
            $cbResult = $this->owner->invokeWithExtensions('beforeVerification', $result, $r);
            if ($cbResult !== null) {
                $result = is_bool($cbResult) ? $result && $cbResult : $cbResult;
            }
        }

        if ($cb = $this->verifyCallback) {
            $cbResult = $cb($this->owner, $result, $r);
            if ($cbResult !== null) {
                $result = is_bool($cbResult) ? $result && $cbResult : $cbResult;
            }
        }

        if ($this->owner->hasMethod('afterVerification')) {
            $cbResult = $this->owner->invokeWithExtensions('afterVerification', $result, $r);
            if ($cbResult !== null) {
                $result = is_bool($cbResult) ? $result && $cbResult : $cbResult;
            }
        }

        if ($result instanceof SS_HTTPResponse) {
            return $result;
        }

        if ($r && $r->isAjax()) {
            $response = new SS_HTTPResponse(json_encode($result), 200, 'success');
            $response->addHeader('Content-type', 'application/json');

            return $response;
        }

        return $result;
    }
} 