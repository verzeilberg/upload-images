<?php


namespace verzeilberg\UploadImagesBundle\EventListener;


use verzeilberg\UploadImagesBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

class ImageListener
{

    /** @ORM\PrePersist */
    public function prePersistHandler(
        Image $image,
        LifecycleEventArgs $event
    )
    {
        die('You shall not pass');
    }

    /** @ORM\PostPersist */
    public function postPersistHandler(Image $image, LifecycleEventArgs $event)
    {
        die('You shall not pssssass');
    }

    /** @ORM\PreUpdate */
    public function preUpdateHandler(Image $image, PreUpdateEventArgs $event)
    {

        var_dump($image);
        die('You shall not sdadasdasdpass');
    }

}