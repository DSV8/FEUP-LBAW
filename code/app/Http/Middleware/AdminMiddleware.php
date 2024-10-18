<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            // Check if the user's ID exists in the admin table
            if (DB::table('admin')->where('user_id', auth()->id())->exists()) {
                return $next($request);
            }
        }

        return redirect('/'); // Redirect to the main page
    }
}