<?php
namespace App\Http\Middleware;

use App\Contracts\TokenProvider;
use Closure;
use Illuminate\Http\Request;

final class EnsureFenixAuth
{
    public function __construct(private TokenProvider $tokens) {}

    public function handle(Request $request, Closure $next)
    {
        $userId = (int) auth()->id();
        if (!$userId || !$this->tokens->hasValidForUser($userId)) {
            return redirect()->route('fenix.connect'); // Implement OAuth connect later
        }
        return $next($request);
    }
}
