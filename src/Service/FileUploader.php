<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploader
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function createName(UploadedFile $file): string
    {
        $originalFilename = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );
        $safeFilename = $this->slugger->slug($originalFilename);

        return $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    }

    public function upload(string $folder, UploadedFile $file): string
    {
        $fileName = $this->createName($file);

        try {
            $file->move($folder, $fileName);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }

        return $fileName;
    }
}
