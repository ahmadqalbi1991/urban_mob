<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect(RouteServiceProvider::HOME);
        // }

        if (Auth::guard($guard)->check()) {
            $role = Auth::user()->role; 

            switch ($role) {
                case 'customer':
                return redirect()->route('login')->withInput()->with('error', 'Oppes! Customer not allowed, Use mobile app!');
                break;
                case 'vendor':
                return redirect()->route('shop');
                break; 
                case 'admin':
                return redirect()->route('home');
                break; 
                case 'super-admin':
                return redirect()->route('home');
                break; 

                default:
                return redirect()->route('login'); 
                break;
            }
        }

        return $next($request);
    }
}
