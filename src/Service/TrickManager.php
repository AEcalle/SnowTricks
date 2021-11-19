<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

final class TrickManager
{
    private SluggerInterface $slugger;
    private ImageManager $imageManager;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(
        SluggerInterface $slugger,
        ImageManager $imageManager,
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->slugger = $slugger;
        $this->imageManager = $imageManager;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function save(Trick $trick): void
    {
        $trick->setSlug($this->slugger->slug($trick->getName())->toString());
        $trick->setUser($this->security->getUser());

        $this->imageManager->upload($trick->getImages());

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }
}
