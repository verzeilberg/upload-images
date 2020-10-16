<?php


namespace verzeilberg\UploadImagesBundle\EventListener;


use verzeilberg\UploadImagesBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use verzeilberg\UploadImagesBundle\Service\Image as ImageService;
use Doctrine\ORM\Mapping as ORM;

class ImageListener
{
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
        var_dump($image);
        die('You shall not pass');
    }

    /** @ORM\PreUpdate */
    public function preUpdateHandler(Image $image, PreUpdateEventArgs $event)
    {

        var_dump($image);
        die('You shall not sdadasdasdpass');
    }

}