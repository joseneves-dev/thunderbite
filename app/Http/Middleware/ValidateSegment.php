<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateSegment
{
    public function handle(Request $request, Closure $next)
    {
        // Fetch the segment from the query string
        $segment = $request->query('segment');

        // Define the valid segment options
        $validSegments = ['low', 'med', 'high'];

        // Check if the segment is valid
        if (!in_array($segment, $validSegments)) {
            // Return a custom response if the segment is invalid
            abort(404);
        }

        // Proceed to the next middleware/controller if valid
        return $next($request);
    }
}