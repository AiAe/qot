<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use View;

class Admin
{
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();

        // Verify permissions
        if ($user['role'] == 1) {
            abort(403);
        }

        View::share('loggedUser', $user);

        return $next($request);
    }
}
