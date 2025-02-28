<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthoriseRolePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::find(Auth::id());

       if(!$user->roleHasAccess($request->route()->getName())){
        return response()->json(['error' => 'Your Role does not have this access, contact Administrator'], 403);
       }
        return $next($request);
    }
}
