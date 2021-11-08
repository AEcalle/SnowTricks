<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;    

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $filename;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'images')]
    private ?Trick $trick;

    #[Assert\Image]
    private ?UploadedFile $file = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
    }
}
