<?php

namespace App\Http\Middleware;

use Closure;
use App\Exercise;
use App\User;

class ExerciseOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach ($request->route()->parameters() as $parameter) {
            if ($parameter instanceof Exercise) {
                if (! $request->user()->exercises()->find($parameter->id)) {
                    abort(404);
                }
            }
        }

        return $next($request);
    }
}
