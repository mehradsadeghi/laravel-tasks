<?php

namespace App\Task;

use Illuminate\Support\Facades\Validator;

class ValidateEdit
{
    public function handle($request, $next)
    {
        $v = Validator::make(request()->all(), [
            'state' => 'required|in:not_started,done,doing,failed,wont_do'
        ]);

        if (!$v->fails()) {
            return $next($request);
        }
        // Maybe log something
        return redirect()->back()->withInput()->withErrors($v->errors());
    }
}