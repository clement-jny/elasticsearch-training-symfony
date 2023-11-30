<?php

namespace App\Model;

final class Suggestion
{
    private function __construct(
        public readonly string $text,
    ) {
    }

    public static function create(array $source): self
    {
        return new self(
            text: $source['text']
        );
    }
}
