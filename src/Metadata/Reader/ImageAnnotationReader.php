<?php


namespace verzeilberg\UploadImagesBundle\Metadata\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use verzeilberg\UploadImagesBundle\Mapping\Annotation\UploadField;

class ImageAnnotationReader
{

    public function loadMetadataForClass($class)
    {

        $reader = new AnnotationReader();

        $reflClass = new ReflectionClass($class);
        $property = $reflClass->getProperties();

        var_dump($property);

    }

}