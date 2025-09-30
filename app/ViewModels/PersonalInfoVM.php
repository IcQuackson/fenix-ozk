<?php

namespace App\ViewModels;

use App\Domain\Entities\Person;

final class PersonalInfoVM
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $institutionalEmail,
        public readonly ?string $username,
        public readonly ?string $campus,
        public readonly ?string $photoUri,
    ) {}

    public static function fromDomain(Person $person): self
    {
        $photoUri = null;

        if (! empty($person->photo['data']) && ! empty($person->photo['type'])) {
            $photoUri = sprintf(
                'data:%s;base64,%s',
                $person->photo['type'],
                $person->photo['data']
            );
        }

        return new self(
            name: $person->displayName,
            institutionalEmail: $person->email,
            username: $person->username,
            campus: $person->campus,
            photoUri: $photoUri,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'institutionalEmail' => $this->institutionalEmail,
            'username' => $this->username,
            'campus' => $this->campus,
            'photoUri' => $this->photoUri,
        ];
    }
}
