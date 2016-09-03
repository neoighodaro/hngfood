<?php

namespace HNG\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'slack/commands',
    ];

    /**
     * Handle the request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        $regex = '#' . implode('|', $this->except) . '#';

        if ($this->isReading($request) OR $this->tokensMatch($request) OR preg_match($regex, $request->path()))
        {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
    }
}
