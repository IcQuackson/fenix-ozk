<?php
declare(strict_types=1);

namespace App\Domain\Entities;

use Carbon\CarbonImmutable;

final class DateRange
{
	public function __construct(
		public ?CarbonImmutable $start,
		public ?CarbonImmutable $end,
	) {
	}

	public static function fromApi(array $raw): self
	{
		$tz = self::defaultTz();

		$start = self::parseMaybe($raw['start'] ?? null, $tz);
		$end = self::parseMaybe($raw['end'] ?? null, $tz);

		return new self($start, $end);
	}

	/**
	 * Parse a datetime value if present; otherwise return null.
	 * Accepts strings (ISO-like, "Y-m-d H:i[:s]", day-first), epoch seconds,
	 * and DateTimeInterface instances.
	 */
	private static function parseMaybe(mixed $value, ?string $tz = null): ?CarbonImmutable
	{
		if ($value === null) {
			return null;
		}

		// Support DateTimeInterface directly
		if ($value instanceof \DateTimeInterface) {
			return CarbonImmutable::instance(\DateTimeImmutable::createFromInterface($value));
		}

		// Normalize scalars to string
		if (is_int($value)) {
			// Treat as epoch seconds
			return CarbonImmutable::createFromTimestamp($value, $tz ?? self::defaultTz());
		}

		if (!is_string($value)) {
			// Unknown type; ignore gracefully
			return null;
		}

		$value = trim($value);
		if ($value === '') {
			return null;
		}

		// Epoch seconds (or milliseconds) in string form
		if (ctype_digit($value)) {
			$int = (int) $value;
			// Heuristic: 13+ digits → milliseconds
			if (strlen($value) >= 13) {
				$int = (int) floor($int / 1000);
			}
			return CarbonImmutable::createFromTimestamp($int, $tz ?? self::defaultTz());
		}

		// Otherwise use the strict parser
		return self::parseDateTime($value, $tz);
	}

	private static function parseDateTime(string $value, ?string $tz = null): CarbonImmutable
	{
		$tz = $tz ?? self::defaultTz();
		$value = trim($value);
		if ($value === '') {
			throw new \InvalidArgumentException('Empty datetime string.');
		}

		$isoLike = (bool) preg_match('/^\d{4}-\d{2}-\d{2}[T\s]\d{2}:\d{2}/', $value);
		$dayFirstLike = (bool) preg_match('/^\d{2}[\/-]\d{2}[\/-]\d{4}/', $value);

		// Normalize ISO-like: Z -> +00:00, +0100 -> +01:00, .SSS -> .SSS000 (to microseconds)
		if ($isoLike) {
			$value = preg_replace('/Z$/', '+00:00', $value) ?? $value;
			$value = preg_replace('/([+\-]\d{2})(\d{2})$/', '$1:$2', $value) ?? $value;
			$value = preg_replace_callback('/\.(\d{1,9})/', static function ($m) {
				$frac = substr($m[1], 0, 6);
				return '.' . str_pad($frac, 6, '0');
			}, $value, 1) ?? $value;
		}

		$dayFirst = [
			'd/m/Y H:i:s',
			'd/m/Y H:i',
			'd/m/Y',
			'd-m-Y H:i:s',
			'd-m-Y H:i',
			'd-m-Y',
		];
		$iso = [
			'Y-m-d\TH:i:s.uP',
			'Y-m-d\TH:i:sP',
			'Y-m-d\TH:iP',
			'Y-m-d\TH:i:s.u',
			'Y-m-d\TH:i:s',
			'Y-m-d\TH:i',
			'Y-m-d H:i:s.u',
			'Y-m-d H:i:s',
			'Y-m-d H:i',
			'Y-m-d',
		];

		$formats = $dayFirstLike ? array_merge($dayFirst, $iso) : array_merge($iso, $dayFirst);

		foreach ($formats as $fmt) {
			try {
				$dt = CarbonImmutable::createFromFormat($fmt, $value, $tz);
			} catch (\Throwable) {
				continue;
			}

			if ($dt !== false) {
				$err = \DateTime::getLastErrors();
				if (($err['warning_count'] ?? 0) === 0 && ($err['error_count'] ?? 0) === 0) {
					return $dt;
				}
			}
		}

		// Last resort
		try {
			return CarbonImmutable::parse($value, $tz);
		} catch (\Throwable) {
			throw new \InvalidArgumentException("Unrecognized datetime: {$value}");
		}
	}

	private static function defaultTz(): string
	{
		return \date_default_timezone_get() ?: 'UTC';
	}
}
