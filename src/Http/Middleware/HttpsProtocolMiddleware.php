<?php

namespace HalcyonLaravel\Base\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class HttpsProtocolMiddleware
{
    public function handle($request, Closure $next)
    {
        $scheme = 'https';

        if (strpos(config('app.url'), "$scheme://") !== false) {

            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }

            URL::forceScheme($scheme);
        }

        return $next($request);
    }

}