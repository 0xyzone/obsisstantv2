<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReplaceMarkdownPlaceholders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() && $response->headers->get('content-type') === 'text/html') {
            $content = $response->getContent();
            $content = str_replace('%%register_url%%', route('filament.studio.auth.register'), $content);
            $response->setContent($content);
        }

        return $response;
    }
}
