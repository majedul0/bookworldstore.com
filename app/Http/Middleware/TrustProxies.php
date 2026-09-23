<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Trust all proxies: the app container is never reachable directly
     * from the internet, only through our own nginx chain (host nginx
     * -> docker nginx -> app), so there's no risk of a client spoofing
     * X-Forwarded-* headers straight to it. Without this, Laravel
     * ignores X-Forwarded-Proto and generates http:// URLs even though
     * the site is served over https via Cloudflare, which the browser
     * then blocks as mixed content.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
