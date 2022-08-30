<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $roles = [
            'admin' => [1],
            'meister' => [1,3],
            'manager' => [1,2],
        ];

        if (is_null($request->user()) || !in_array(auth()->user()->roleId, $roles[$role])) {
            return redirect('login');
        }

        return $next($request);
    }
}
