<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\CommentManager;
use App\Service\FormHandler;
use App\Service\TrickManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route('trick-create', name: 'trickCreate')]
    public function create(FormHandler $formHandler, TrickManager $trickManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $formHandler->handle(
            new Trick(),
            function ($trick) use ($trickManager) {
                $trickManager->save($trick);

                return $this->redirectToRoute('trickCreate');
            },
            'Trick saved !'
        );

        return $this->renderForm('trick/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('trick-update/{id}', name: 'trickUpdate')]
    public function update(
        Trick $trick,
        FormHandler $formHandler,
        TrickManager $trickManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $formHandler->handle(
            $trick,
            function (Trick $trick) use ($trickManager) {
                $trickManager->save($trick);

                return $this->redirectToRoute('trickUpdate', ['id' => $trick->getId()]);
            },
            'Trick saved !'
        );

        return $this->renderForm('trick/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('delete', subject: 'trick')]
    #[Route('trick-delete/{id}', name: 'trickDelete')]
    public function delete(Trick $trick, Filesystem $filesystem): Response
    {
        foreach ($trick->getImages() as $image) {
            $filesystem->remove('build/images/'.$image->getFilename());
        }

        $this->getDoctrine()->getManager()->remove($trick);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('notice', 'Trick deleted !');

        return $this->redirectToRoute('home');
    }

    #[Route('/trick/{slug}', name: 'trick')]
    public function show(Trick $trick, Request $request, CommentRepository $commentRepository, CommentManager $commentManager): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentManager->save($comment, $trick);

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
                'comments' => $commentRepository->findBy(['trick' => $trick->getId()], [], 5, 0),
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
