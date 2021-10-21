<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommentController extends AbstractController
{
    #[Route('comment/loadMore/{id}/{index}', name: 'commentLoadMore')]
    public function index(Trick $trick, int $index, CommentRepository $repo): Response
    {
        $comments = $repo->findBy(['trick' => $trick->getId()], [], 5, $index);

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer($classMetadataFactory)];
        $serializer = new Serializer($normalizers);

        $data = $serializer->normalize($comments, null, ['groups' => 'group1']);

        return $this->json($data);
    }
}
