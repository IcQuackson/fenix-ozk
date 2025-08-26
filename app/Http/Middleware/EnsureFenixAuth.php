<?php
namespace App\Http\Middleware;

use App\Contracts\TokenProvider;
use Closure;
use Illuminate\Http\Request;

final class EnsureFenixAuth
{
    public function __construct(private TokenProvider $tokens)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = (int) auth()->id();
        if (!$this->tokens->hasValidForUser($userId)) {
            return redirect()->route('fenix.connect');
        }
        return $next($request);
    }
}
