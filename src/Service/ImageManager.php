<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Image;
use Doctrine\Common\Collections\Collection;

final class ImageManager
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param Collection<int,Image> $images
     */
    public function upload(Collection $images): void
    {
        foreach ($images as $image) {
            if (null !== $image->getFile()) {
                $fileName = $this->fileUploader->upload(
                    'build/images/',
                    $image->getFile()
                );
                $image->setFilename($fileName);
            }
        }
    }
}
