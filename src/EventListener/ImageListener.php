<?php


namespace verzeilberg\UploadImagesBundle\EventListener;


use verzeilberg\UploadImagesBundle\Entity\Image;

class ImageListener
{

    public function prePersistHandler(
        Image $image,
        LifecycleEventArgs $event
    )
    {
        die('You shall not pass');
    }


    public function postPersistHandler(Image $image, LifecycleEventArgs $event)
    {
        die('You shall not pssssass');
    }


    public function preUpdateHandler(Image $image, PreUpdateEventArgs $event)
    {
        die('You shall not sdadasdasdpass');
    }

}