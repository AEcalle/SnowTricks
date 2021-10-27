<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    #[Route('trick-create', name: 'trickCreate')]
    public function create(Request $request, SluggerInterface $slugger, FileUploader $fileUploader, UserRepository $repo_u): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setSlug($slugger->slug($trick->getName())->toString());
            //TODO : Change this with connected User
            $trick->setUser($repo_u->findBy([], [], 1, 0)[0]);

            foreach ($form['images'] as $image) {
                $file = $fileUploader->upload(
                    'build/images/',
                    $image['filename']->getData()
                );
                $image->getData('image')->setFileName($file);
            }

            $this->getDoctrine()->getManager()->persist($trick);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Trick saved !');

            return $this->redirectToRoute('trickCreate');
        }

        return $this->renderForm('trick/create.html.twig', [
            'form' => $form,
        ]);
    }

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

        return $this->renderForm(
            'trick/show.html.twig',
            [
                'trick' => $trick,
                'form' => $form,
                'comments' => $repo_c->findBy(['trick' => $trick->getId()], [], 5, 0),
            ]
        );
    }

    #[Route('trick/loadMore/{index}', name: 'trickLoadMore')]
    public function loadMore(int $index, TrickRepository $repo): Response
    {
        return $this->render(
            'trick/_trick_template.html.twig',
            [
                'tricks' => $repo->findBy([], ['name' => 'ASC'], 15, $index),
            ]
        );
    }
}
