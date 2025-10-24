<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isMember() && $user->member) {
            if ($user->member->status !== 'active') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Your member account is not active. Please contact admin.');
            }
        }

        return $next($request);
    }
}
