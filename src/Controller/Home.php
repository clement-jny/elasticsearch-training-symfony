<?php

namespace App\Controller;

use App\Repository\Elastic\BookRepository;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Home extends AbstractController
{
    public function __construct(private BookRepository $bookRepository, private SuggestionRepository $suggestionRepository)
    {
    }

    #[Route(path: '/', name: 'home')]
    public function __invoke(Request $request): Response
    {
        $books = $this->bookRepository->findBooks($request->get('search', ''));
        $suggestions = $this->suggestionRepository->getSuggestions($request->get('search', ''));

        return $this->render(
            view: 'result.html.twig',
            parameters: ['books' => $books, 'suggestions' => $suggestions],
        );
    }
}
