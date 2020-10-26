<?php

namespace App\Http\Middleware;

class Redirect
{
    public function handle($req, $next, $name)
    {
        /**
         * @var $data \Illuminate\Http\JsonResponse
         */
        $data = $next($req);

        if (! $data->getOriginalContent()) {
            return $data;
        }

        return redirect()->route($name, $data->getOriginalContent());
    }
}