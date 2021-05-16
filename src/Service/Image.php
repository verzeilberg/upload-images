<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use verzeilberg\UploadImagesBundle\Exceptions\ImageException;
use verzeilberg\UploadImagesBundle\Metadata\Reader\Annotation;
use verzeilberg\UploadImagesBundle\Entity\Image as ImageObject;

class Image
{
    /** @var ParameterBagInterface */
    private $params;
    /** @var mixed  */
    private $imageFile;
    private $imageWidth;
    private $imageHeight;
    private $imageMimeType;
    private $imageSize;
    private $imageType;


    public function __construct(
        ParameterBagInterface $params
    )
    {
        $this->params       = $params->get("upload_images");
    }

    /**
     * Set image properties into class variables
     */
    private function setImageProperties()
    {
        list(
            $this->imageWidth,
            $this->imageHeight,
            ) = getimagesize($this->imageFile->getPathname());
        $this->imageMimeType    = $this->imageFile->getMimeType();
        $this->imageSize        = $this->imageFile->getSize();
        $this->imageType        = 'default';
    }

    /**
     * Set image file
     * @param $image
     */
    public function setImage($image)
    {
        $this->imageFile    = $image->getImageFile();
        $this->setImageProperties();
    }

    /**
     * Check if uploaded image sets the requirements
     * @return bool
     */
    public function check(): bool
    {
        if ($this->imageSize > $this->params[$this->imageType]['max_file_size'])
        {
            $message = 'File size exceeded max file size';
        } else if (!in_array($this->imageMimeType, $this->params[$this->imageType]['allowed_file_types']))
        {
            $message = 'File mime type not allowed';
        } else {
            foreach ($this->params[$this->imageType]['image_types'] as $type => $imageType)
            {
                if (isset($imageType['ratio'])) {
                    if ($imageType['ratio']['width'] > $this->imageWidth)
                    {
                        $message = 'File width is to small to create a crop';
                    } else if ($imageType['ratio']['height'] > $this->imageHeight)
                    {
                        $message = 'File height is to small to create a crop';
                    }
                }
            }
        }
        if (isset($message)) {
            throw new ImageException($message);
        }
        return true;
    }

    /**
     * Create folder, if not exist, and give appropriate rights
     */
    public function createDestinationFolder($subFolder = null)
    {
        // Set destination folder
        $destinationFolder = $this->params[$this->imageType]['upload_folder'] . (isset($subFolder)? $subFolder . '/': null);
        if (!file_exists($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        } elseif (!is_writable($destinationFolder)) {
            chmod($destinationFolder, 0777);
        }
        return $destinationFolder;
    }

    /**
     * @param $destination
     * @param null $fileName
     */
    public function uploadImage($destination, $fileName = null)
    {
        if (isset($fileName))
        {
            $fileName = Urlizer::urlize($fileName).'-'.uniqid().'.'.$this->imageFile->guessExtension();
        } else {
            $originalFilename = pathinfo($this->imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$this->imageFile->guessExtension();
        }
        $this->imageFile->move($destination, $fileName);
    }

    
}