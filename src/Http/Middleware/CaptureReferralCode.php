<?php

namespace Juzaweb\Modules\Referral\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CaptureReferralCode
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref') && setting('enable_referral_system', false)) {
            $ref = $request->query('ref');

            // Default to 30 days if not set
            $minutes = (int) setting('referral_cookie_expiration', 60 * 24 * 30);

            // Save referral code in cookie
            cookie()->queue(cookie('referral_code', $ref, $minutes));
        }

        return $next($request);
    }
}
