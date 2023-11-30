<?php

namespace App\Model\Elastic;

final class Author
{
    private function __construct(
        public readonly string $title,
        public readonly string $fullName,
        public readonly string $email
    ) {
    }

    public static function create(array $source): self
    {
        return new self(
            title: $source['title'],
            fullName: $source['fullname'],
            email: $source['email']
        );
    }
}
