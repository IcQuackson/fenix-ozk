<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class FenixAuthController extends Controller
{
    public function connect(Request $request)
    {
        $cfg = config('services.fenix');
        $state = Str::random(32);
        $request->session()->put('fenix_oauth_state', $state);

        // Use explicit redirect from config (exactly as registered at Fenix)
        $redirectUri = $cfg['redirect_uri'] ?: route('fenix.callback', [], true);

        $qs = http_build_query([
            'client_id' => $cfg['client_id'],
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ]);

        return redirect()->away($cfg['authorize_url'] . '?' . $qs);
    }

    public function callback(Request $request)
    {
        $expected = $request->session()->pull('fenix_oauth_state');
        $state = (string) $request->query('state');

        if (!$expected || !hash_equals($expected, $state)) {
            abort(419, 'Invalid OAuth state');
        }

        if ($error = $request->query('error')) {
            return redirect()->route('login')->with('error', "Fenix auth failed: {$error}");
        }

        $code = (string) $request->query('code');
        if ($code === '') {
            return redirect()->route('login')->with('error', 'Missing authorization code');
        }

        $cfg = config('services.fenix');
        $redirectUri = $cfg['redirect_uri'] ?: route('fenix.callback', [], true);

        // Exchange code for tokens (form POST)
        $token = Http::asForm()
            ->retry(2, 200)
            ->timeout(10)
            ->post($cfg['access_token_url'], [
                'client_id' => $cfg['client_id'],
                'client_secret' => $cfg['client_secret'],
                'redirect_uri' => $redirectUri,
                'code' => $code,
                'grant_type' => 'authorization_code',
            ])->throw()->json();

        $accessToken = $token['access_token'] ?? null;
        $refreshToken = $token['refresh_token'] ?? null;
        $expiresIn = (int) ($token['expires_in'] ?? 3600);

        if (!$accessToken || !$refreshToken) {
            return redirect()->route('login')->with('error', 'Fenix response missing tokens');
        }

        // Identify user
        $person = Http::withToken($accessToken)
            ->retry(2, 200)
            ->timeout(10)
            ->acceptJson()
            ->get(rtrim($cfg['base_url'], '/') . '/person')
            ->throw()
            ->json();

        $fenixId = (string) ($person['istId'] ?? $person['username'] ?? $person['id'] ?? '');
        $name = (string) ($person['name'] ?? 'Fenix User');
        $email = (string) ($person['email'] ?? ($person['emails'][0] ?? 'unknown@example.test'));

        if ($fenixId === '') {
            return redirect()->route('login')->with('error', 'Fenix /person missing identifier');
        }

        // Upsert user
        $user = User::query()
            ->where('fenix_person_id', $fenixId)
            ->orWhere('email', $email)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'fenix_person_id' => $fenixId,
                'password' => bcrypt(Str::random(32)),
            ]);
        } else {
            $user->forceFill([
                'name' => $name,
                'email' => $email,
                'fenix_person_id' => $user->fenix_person_id ?: $fenixId,
            ])->save();
        }

        // Save tokens
        DB::table('fenix_tokens')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'access_token_expires_at' => now()->addSeconds($expiresIn),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'))->with('success', 'Signed in via Fenix ✅');
    }
}
