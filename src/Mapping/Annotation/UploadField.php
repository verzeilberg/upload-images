<?php


namespace verzeilberg\UploadImagesBundle\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class UploadField
{

    /**
     * @var string
     */
    protected $mapping;

}