<?php


namespace verzeilberg\UploadImagesBundle\Metadata\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use ReflectionException;
use verzeilberg\UploadImagesBundle\Mapping\Annotation\UploadField;

class ImageAnnotationReader
{

    /**
     * @throws ReflectionException
     */
    public function loadMetadataForClass($class)
    {

        $reader = new AnnotationReader();

        $reflClass = new ReflectionClass($class);

        $trace = debug_backtrace();


        var_dump($trace); die;


        $properties = $reflClass->getProperties();

        $reader = new AnnotationReader();



        foreach($properties as $property) {
            $uploadField = $reader->getPropertyAnnotation($property, UploadField::class);

            var_dump($uploadField);

            if (null === $uploadField) {
                continue;
            }
        }

        die;

    }

}