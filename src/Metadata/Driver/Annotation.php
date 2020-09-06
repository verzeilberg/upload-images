<?php


namespace verzeilberg\UploadImagesBundle\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use verzeilberg\UploadImagesBundle\Mapping\Annotation\UploadField;

class Annotation
{

    /** @var AnnotationReader */
    protected $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function loadMetadataForClass(\ReflectionClass $class)
    {

        $properties = [];
        $properties = \array_merge($properties, $class->getProperties());

        var_dump($properties); die;

        foreach ($properties as $property) {
            $myAnnotation = $this->reader->getPropertyAnnotation($property, UploadField::class);
        }

        echo $myAnnotation->myProperty; // result: "value"
    }

}