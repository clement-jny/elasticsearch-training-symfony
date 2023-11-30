<?php

namespace App\Repository\Elastic;

use App\Model\Elastic\Book;
use Elastica\Client;
use Elastica\Result;
use Elastica\Index;

class BookRepository
{
    private Index $index;

    public function __construct(Client $elastic)
    {
        $this->index = $elastic->getIndex('library');
    }

    private function createBook(Result $result): Book
    {
        return Book::create($result->getSource(), $result->getHighlights());
    }

    /** @return list<Book> */
    public function findBooks(string $search): array
    {
        $query = [];
        $results = [];

        if ($search !== '') {
            $query = [
                'query' => [
                    'multi_match' => [
                        'query' => $search,
                        'fields' => ['title^3', 'author.fullname^2', 'description^1']
                    ]
                ],
                'highlight' => [
                    'fields' => [
                        'title' => new \stdClass(),
                        'author.fullname' => new \stdClass(),
                        'description' => new \stdClass(),
                    ],
                    'pre_tags' => ['<span style="background-color: #FFFF00">'],
                    'post_tags' => ['</span>']
                ]
            ];

            $results = $this->index->search($query)->getResults();
        } else {
            $results = $this->index->search($query)->getResults();
        }

        return array_map('self::createBook', $results);
    }
}
