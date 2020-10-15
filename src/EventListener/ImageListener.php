<?php


namespace verzeilberg\UploadImagesBundle\EventListener;


use verzeilberg\UploadImagesBundle\Entity\Image;

class ImageListener
{

    /** @PrePersist */
    public function prePersistHandler(
        Image $image,
        LifecycleEventArgs $event
    ) {
            die('You shall not pass');
    }

}