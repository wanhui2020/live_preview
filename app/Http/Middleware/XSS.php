<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

class XSS

{

    public function handle(Request $request, Closure $next)

    {

        $input = $request->all();

        $request->merge($input);

        return $next($request);

    }

}