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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    #[Route('trick-create', name: 'trickCreate')]
    public function create(Request $request, SluggerInterface $slugger, FileUploader $fileUploader, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setSlug($slugger->slug($trick->getName())->toString());
            //TODO : Change this with connected User
            $trick->setUser($userRepository->findBy([], [], 1, 0)[0]);

            foreach ($trick->getImages() as $image) {
                if (null !== $image->getFile()) {
                    $fileName = $fileUploader->upload(
                        'build/images/',
                        $image->getFile()
                    );
                    $image->setFileName($fileName);
                }
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

    #[Route('trick-update/{id}', name: 'trickUpdate')]
    public function update(Trick $trick, Request $request,
    SluggerInterface $slugger, FileUploader $fileUploader,
    UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(TrickType::class, $trick)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setSlug($slugger->slug($trick->getName())->toString());
            //TODO : Change this with connected User
            $trick->setUser($userRepository->findBy([], [], 1, 0)[0]);

            foreach ($trick->getImages() as $image) {
                if (null !== $image->getFile()) {
                    $fileName = $fileUploader->upload(
                        'build/images/',
                        $image->getFile()
                    );
                    $image->setFilename($fileName);
                }
            }

            $this->getDoctrine()->getManager()->persist($trick);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Trick saved !');

            return $this->redirectToRoute('trickUpdate', ['id' => $trick->getId()]);
        }

        return $this->renderForm('trick/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted("delete", subject: "trick")]
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
    public function show(Trick $trick, Request $request, CommentRepository $commentRepository, UserRepository $userRepository): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setTrick($trick);
            //TODO : Change this with connected User
            $comment->setUser($userRepository->findBy([], [], 1, 0)[0]);

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
