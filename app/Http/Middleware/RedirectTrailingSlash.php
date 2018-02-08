<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;

class RedirectTrailingSlash {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->method() === Request::METHOD_GET && !$request->headers->get('X-PJAX')) {

            if (null !== $qs = $request->getQueryString()) {
                $qs = '?' . $qs;
            }

            $headers = $request->headers->all();
            $scheme = $request->getSchemeAndHttpHost();
            $baseUrl = $request->getBaseUrl();
            $pathInfo = $request->getPathInfo();
            $originalUri = $scheme . $baseUrl . $pathInfo . $qs;

            $pathInfo = preg_replace('/(\/){2,}/', '$1', $pathInfo);

            if($pathInfo !== '/') {
//                if (preg_match('/\./', $pathInfo)) {
//                    if (!preg_match('/.+\.[^\/]+$/', $request->getPathInfo())) {
//                        $pathInfo = rtrim($pathInfo, '/');
//                    }
//                } else {
//
//                }
                    if (preg_match('/.*\/$/', $pathInfo)) {
                    $pathInfo = rtrim($pathInfo, '/');
                }
            } else {
                $pathInfo = '/';
            }

            $newUri = $scheme . $baseUrl . $pathInfo . $qs;

            if ($originalUri !== $newUri) {
                return Redirect::to($newUri, 301, $headers);
            }
        }

        return $next($request);
    }

}