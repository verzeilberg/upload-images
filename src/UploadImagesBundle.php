<?php


namespace verzeilberg\UploadImagesBundle;

use verzeilberg\UploadImagesBundle\DependencyInjection\UploadImagesExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UploadImagesBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new UploadImagesExtension();
        }
        return $this->extension;
    }
}