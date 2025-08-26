<?php
namespace App\Contracts;

interface TokenProvider
{
    public function forUser(int $userId): ?string; // access token
    public function refreshForUser(int $userId): ?string; // returns fresh access token
    public function hasValidForUser(int $userId): bool;
}
