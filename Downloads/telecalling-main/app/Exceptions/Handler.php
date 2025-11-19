<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * Handle unauthenticated requests.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Force JSON response for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please provide a valid token.',
            ], 401);
        }

        // Redirect to login for web-based requests
        return redirect()->guest(route('login'));
    }
}
