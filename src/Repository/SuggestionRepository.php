<?php

namespace App\Repository;

use App\Model\Suggestion;
use Elastica\Client;
use Elastica\Result;
use Elastica\Index;

class SuggestionRepository
{
    private Index $index;

    public function __construct(Client $elastic)
    {
        $this->index = $elastic->getIndex('library');
    }

    /** @return list<Suggestion> */
    public function getSuggestions(string $search): array
    {
        if (strlen($search) >= 3) {
            $query = [
                'suggest' => [
                    'suggest' => [
                        'title-suggestion' => [
                            'text' => $search,
                            'term' => [
                                'field' => 'title'
                            ]
                        ]
                    ]
                ]
            ];

            $suggestions = $this->index->search($query)->getSuggests();

            return array_map(
                static fn (array $result) => Suggestion::create($result),
                $suggestions['title-suggestion'][0]['options']
            );
        }

        return [];
    }
}
