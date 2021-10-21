<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route('/trick/{slug}', name: 'trick')]
    public function show(Trick $trick, Request $request, CommentRepository $repo_c, UserRepository $repo_u): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setTrick($trick);
            //TODO : Change this with connected User
            $comment->setUser($repo_u->findBy([], [], 1, 0)[0]);

            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Success !');

            return $this->redirectToRoute(
                'trick',
                [
                    'slug' => $trick->getSlug(),
                    '_fragment' => 'CommentForm',
                ]
            );
        }

        $comments = $repo_c->findBy(['trick' => $trick->getId()], [], 5, 0);

        return $this->render(
            'trick/show.html.twig',
            [
                'trick' => $trick,
                'form' => $form->createView(),
                'comments' => $comments,
            ]
        );
    }
}
