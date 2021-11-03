<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(TrickRepository $repo): Response
    {
        return $this->render(
            'home/index.html.twig',
            [
                'tricks' => $repo->findBy([], ['name' => 'ASC'], 15, 0),
            ]
        );
    }
}
