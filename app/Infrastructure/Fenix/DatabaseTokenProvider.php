<?php
namespace App\Infrastructure\Fenix;

use App\Contracts\TokenProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

final class DatabaseTokenProvider implements TokenProvider
{
	public function forUser(int $userId): ?string
	{
		$row = DB::table('fenix_tokens')->where('user_id', $userId)->first();
		if (!$row)
			return null;
		if ($row->access_token && $row->access_token_expires_at && Carbon::parse($row->access_token_expires_at)->isFuture()) {
			return $row->access_token;
		}
		return $this->refreshForUser($userId);
	}

	public function hasValidForUser(int $userId): bool
	{
		$row = DB::table('fenix_tokens')->where('user_id', $userId)->first();
		return $row && $row->access_token && Carbon::parse($row->access_token_expires_at)->isFuture();
	}

	public function refreshForUser(int $userId): ?string
	{
		$row = DB::table('fenix_tokens')->where('user_id', $userId)->first();
		if (!$row || !$row->refresh_token)
			return null;

		$cfg = config('services.fenix');

		$resp = Http::asForm()
			->retry(2, 200)
			->timeout(10)
			->post($cfg['refresh_token_url'], [
				'client_id' => $cfg['client_id'],
				'client_secret' => $cfg['client_secret'],
				'refresh_token' => $row->refresh_token,
				'grant_type' => 'refresh_token',
			])->throw()->json();

		DB::table('fenix_tokens')->updateOrInsert(
			['user_id' => $userId],
			[
				'access_token' => $resp['access_token'] ?? null,
				'access_token_expires_at' => now()->addSeconds((int) ($resp['expires_in'] ?? 3600)),
				'updated_at' => now(),
			]
		);

		return $resp['access_token'] ?? null;
	}
}
