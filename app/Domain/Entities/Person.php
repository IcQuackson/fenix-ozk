<?php

namespace App\Domain\Entities;

final class Person
{
    public function __construct(
        public readonly ?string $username,
        public readonly ?string $fullName,
        public readonly ?string $displayName,
        public readonly ?string $email,
        public readonly ?string $campus,
        public readonly ?string $gender,
        public readonly ?string $birthday,
        /** @var string[] */
        public readonly array $personalEmails,
        /** @var string[] */
        public readonly array $workEmails,
        /** @var string[] */
        public readonly array $webAddresses,
        /** @var string[] */
        public readonly array $workWebAddresses,
        /** @var array */
        public readonly array $roles,
        public readonly ?array $photo, // { type, data } with base64 photo
    ) {}

    public static function fromApi(array $raw): self
    {
        return new self(
            username: $raw['username'] ?? null,
            fullName: $raw['name'] ?? null,
            displayName: $raw['displayName'] ?? null,
            email: $raw['email'] ?? null,
            campus: $raw['campus'] ?? null,
            gender: $raw['gender'] ?? null,
            birthday: $raw['birthday'] ?? null,
            personalEmails: $raw['personalEmails'] ?? [],
            workEmails: $raw['workEmails'] ?? [],
            webAddresses: $raw['webAddresses'] ?? [],
            workWebAddresses: $raw['workWebAddresses'] ?? [],
            roles: $raw['roles'] ?? [],
            photo: $raw['photo'] ?? null,
        );
    }
}
