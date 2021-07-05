<?php


namespace verzeilberg\UploadImagesBundle\EventListener;


use verzeilberg\UploadImagesBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use verzeilberg\UploadImagesBundle\Entity\ImageType;
use verzeilberg\UploadImagesBundle\Service\Image as ImageService;
use Doctrine\ORM\Mapping as ORM;

class ImageListener
{
    /** @var ImageService  */
    protected $imageService;

    public function __construct(
        ImageService $imageService
    )
    {
        $this->imageService = $imageService;
    }

    /** @ORM\PrePersist */
    public function prePersistHandler(
        Image $image,
        LifecycleEventArgs $event
    )
    {
        $this->processImageBeforeSave($image);
    }

    /** @ORM\PreUpdate */
    public function preUpdateHandler(Image $image, PreUpdateEventArgs $event)
    {
        $this->processImageBeforeSave($image);
    }

    /**
     * Process image before save image entity
     * @param $image
     */
    private function processImageBeforeSave($image)
    {
        $this->imageService->setImage($image);
        $result = $this->imageService->check();
        if ($result)
        {
            $destinationFolder = $this->imageService->createDestinationFolder('original');
            $fileName = $this->imageService->createFileName();
            $this->imageService->uploadImage($destinationFolder, $fileName);
            $image->setNameImage($fileName);
            $imageType = $this->imageService->createImageType();
            $image->addImageType($imageType);

            $this->imageService->processImageTypes();

        }
    }

}