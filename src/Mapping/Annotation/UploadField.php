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

    /**
     * @return string
     */
    public function getMapping(): string
    {
        return $this->mapping;
    }

    /**
     * @param string $mapping
     */
    public function setMapping(string $mapping): void
    {
        $this->mapping = $mapping;
    }



}