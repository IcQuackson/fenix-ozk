<?php
namespace App\Contracts;

interface FenixPort
{
    /** @return array<int,array<string,mixed>> */
    public function listCourses(int $userId): array;
    /** @return array<int,array<string,mixed>> */
    public function listGrades(int $userId): array;
    /** @return array<string,mixed> */
    public function me(int $userId): array;
}
