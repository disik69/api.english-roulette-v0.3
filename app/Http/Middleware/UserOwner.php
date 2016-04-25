<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class UserOwner
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
            if ($parameter instanceof User) {
                if (
                    (! $request->user()->is('admin')) &&
                    ($request->user()->id !== $parameter->id)
                ) {
                    abort(404);
                }
            }
        }

        return $next($request);
    }
}
