protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
{
// Replace with your own list of “active” user IDs
$activeUserIds = [1];
foreach ($activeUserIds as $uid) {
$schedule->job(new \App\Jobs\RefreshUserSlices($uid))->everyFifteenMinutes();
}
}
