<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateFornecedor
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('fornecedor_id')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('portal.login');
        }

        return $next($request);
    }
}
