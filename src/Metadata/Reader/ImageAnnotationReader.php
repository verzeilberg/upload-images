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
        $properties = $reflClass->getProperties();

        $reader = new AnnotationReader();

        foreach($properties as $property) {
            $uploadField = $reader->getPropertyAnnotation($property, UploadField::class);
            if (null === $uploadField) {
                continue;
            }
        }
    }

}