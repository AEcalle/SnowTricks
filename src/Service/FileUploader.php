<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploader
{
    public function createName(UploadedFile $file): string
    {
        $extension = $file->guessExtension();
        if (! $extension) {
            $extension = 'bin';
        }

        return rand(1, 99999).'.'.$extension;
    }

    public function upload(string $folder, UploadedFile $file): string
    {
        $filesystem = new Filesystem();
        do {
            $fileName = $this->createName($file);
        } while ($filesystem->exists($folder.$fileName));

        try {
            $file->move($folder, $fileName);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }

        return $fileName;
    }
}
