<?php

namespace App\Http\Middleware;

class View
{
    public function handle($req, $next, $name)
    {
        /**
         * @var $data \Illuminate\Http\JsonResponse
         */
        $data = $next($req);
        if (!$data->getOriginalContent()) {
            return $data;
        }
        return response()->view($name, $data->getOriginalContent());
    }
}