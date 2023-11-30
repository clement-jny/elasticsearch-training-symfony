<?php

namespace App\Model\Elastic;

final class Book
{
    private function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $price,
        public readonly string $authorFullname
    ) {
    }

    public static function create(array $source, array $highlight): self
    {
        return new self(
            title: $highlight['title'][0] ?? $source['title'],
            description: $highlight['description'][0] ?? $source['description'],
            price: $source['price'],
            authorFullname: $highlight['author.fullname'][0] ?? $source['author']['fullname']
        );
    }
}
