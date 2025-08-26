public function register(): void
{
$this->renderable(function (\RuntimeException $e, $request) {
if (str_contains($e->getMessage(), 'Fenix API')) {
if ($request->wantsJson()) {
return response()->json(['message' => 'Fenix currently unavailable'], 503);
}
return response()->view('errors.fenix', ['message' => 'Live data unavailable (serving cache).'], 503);
}
});
}
