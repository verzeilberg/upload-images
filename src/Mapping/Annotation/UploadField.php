<?php


namespace verzeilberg\UploadImagesBundle\Mapping\Annotation;

/**
 * @Annotation
 */
final class UploadField
{

    /**
     * @var string
     */
    protected $mapping;

    /**
     * Constructs a new instance of UploadableField.
     *
     * @param array $options The options
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options)
    {
        if (empty($options['mapping'])) {
            throw new \InvalidArgumentException('The "mapping" attribute of UploadableField is required.');
        }

        foreach ($options as $property => $value) {
            if (!\property_exists($this, $property)) {
                throw new \RuntimeException(\sprintf('Unknown key "%s" for annotation "@%s".', $property, \get_class($this)));
            }

            $this->$property = $value;
        }
    }

}