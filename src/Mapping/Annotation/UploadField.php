<?php


namespace verzeilberg\UploadImagesBundle\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class UploadField
{

    /**
     * @var string
     */
    public $mapping;

}