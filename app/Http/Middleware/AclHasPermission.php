<?php

namespace App\Http\Middleware;

use Closure;

class AclHasPermission extends \Kodeine\Acl\Middleware\HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;

        // override crud resources via config
        $this->crudConfigOverride();

        // if route has access
        if (( ! $this->getAction('is') or $this->hasRole()) and
            ( ! $this->getAction('can') or $this->hasPermission()) and
            ( ! $this->getAction('protect_alias') or $this->protectMethods())
        ) {
            return $next($request);
        }

        return response()->json(['errors' => ['You are not authorized to access this resource.']], 401);
    }
}