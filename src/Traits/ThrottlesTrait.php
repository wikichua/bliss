<?php

namespace Wikichua\Bliss\Traits;

use Illuminate\Http\Request;

trait ThrottlesTrait
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    protected function setMaxAttempts($value = 5)
    {
        $this->maxAttempts = $value;
    }

    protected function setDecayMinutes($value = 1)
    {
        $this->decayMinutes = $value;
    }

    protected function throttleKey(Request $request)
    {
        return \Str::lower(get_throttle_key($request).'|'.$request->ip());
    }

    protected function limiter()
    {
        return app(\Illuminate\Cache\RateLimiter::class);
    }

    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }

    protected function hasTooManyHitAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts()
        );
    }

    protected function incrementHitAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes() * 60
        );
    }

    protected function clearHitAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function sendTooManyHitAttemptsResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw \Illuminate\Validation\ValidationException::withMessages([
            '' => trans('Sorry, there were too many request attempts. Please try again later.'),
        ])->status(\Illuminate\Http\Response::HTTP_TOO_MANY_REQUESTS);
    }
}
/* usage
if ($this->hasTooManyHitAttempts($request)) {
    return $this->sendTooManyHitAttemptsResponse($request);
}
$this->incrementHitAttempts($request);
*/
