use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

RateLimiter::for('fenix-api', function (Request $request) {
    return [Limit::perMinute(120)->by(optional($request->user())->id ?: $request->ip())];
});
