<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('comment/loadMore/{id}/{index}', name: 'commentLoadMore')]
    public function loadMore(Trick $trick, int $index, CommentRepository $commentRepository): Response
    {
        return $this->render(
            'trick/_comment_template.html.twig',
            [
                'comments' => $commentRepository->findBy(['trick' => $trick->getId()], [], 5, $index),
            ]
        );
    }
}
