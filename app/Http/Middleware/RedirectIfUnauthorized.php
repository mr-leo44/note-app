<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfUnauthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Exemple de condition : l'utilisateur n'est pas admin
        if (Auth::user()->account->accountable_type === Admin::class) {
            return $next($request);
        }
        return redirect()->back()->with('warning', 'Vous n\'avez pas à cette zone.');

    }
}
