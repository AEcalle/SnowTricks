<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class CommentManager
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function save(Comment $comment, Trick $trick): void
    {
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setTrick($trick);
        $comment->setUser($this->security->getUser());

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
