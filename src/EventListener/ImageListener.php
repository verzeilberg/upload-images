<?php


namespace verzeilberg\UploadImagesBundle\EventListener;


use verzeilberg\UploadImagesBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use verzeilberg\UploadImagesBundle\Entity\ImageType;
use verzeilberg\UploadImagesBundle\Metadata\Reader\ImageAnnotationReader;
use verzeilberg\UploadImagesBundle\Service\Image as ImageService;
use Doctrine\ORM\Mapping as ORM;

class ImageListener
{
    /** @var ImageService  */
    protected ImageService $imageService;

    public function __construct(
        ImageService $imageService
    )
    {
        $this->imageService = $imageService;
    }

    /** @ORM\PrePersist */
    public function prePersistHandler(Image $image, LifecycleEventArgs $event)
    {
        //$reader = new ImageAnnotationReader();
        //$reader->loadMetadataForClass($image);
        $this->processImageBeforeSave($image);
    }

    /** @ORM\PostPersist */
    public function postPersistHandler(Image $image, LifecycleEventArgs $event)
    {
        $imageType = $this->imageService->createImageType($image);
        $this->imageService->processImageTypes($imageType->getFolder(), $image->getNameImage());
    }


    /** @ORM\PreUpdate */
    public function preUpdateHandler(Image $image, PreUpdateEventArgs $event)
    {

    }


    /** @ORM\PostUpdate */
    public function postUpdateHandler(Image $image, LifecycleEventArgs $event)
    {

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
        }
    }

}