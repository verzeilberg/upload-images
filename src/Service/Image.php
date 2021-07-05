<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use verzeilberg\UploadImagesBundle\Entity\ImageType;
use verzeilberg\UploadImagesBundle\Exceptions\ImageException;
use verzeilberg\UploadImagesBundle\Metadata\Reader\Annotation;
use verzeilberg\UploadImagesBundle\Entity\Image as ImageObject;
use verzeilberg\UploadImagesBundle\Repository\ImageTypeRepository;

class Image
{
    /** @var ImageTypeRepository  */
    private $imageTypeRepository;
    /** @var ParameterBagInterface */
    private $params;
    /** @var mixed  */
    private $image;
    /** @var int */
    private $imageWidth;
    /** @var int */
    private $imageHeight;
    /** @var string */
    private $imageMimeType;
    /** @var int */
    private $imageSize;
    /** @var string */
    private $imageType;
    /** @var string */
    private $fileName;
    /** @var string */
    private $destination;

    /**
     * Image constructor.
     * @param ParameterBagInterface $params
     * @param ImageTypeRepository $imageTypeRepository
     */
    public function __construct(
        ParameterBagInterface $params,
        ImageTypeRepository $imageTypeRepository
    )
    {
        $this->params               = $params->get("upload_images");
        $this->imageTypeRepository  = $imageTypeRepository;
    }

    /**
     * Set image properties into class variables
     */
    private function setImageProperties()
    {
        $imageDetails           = $this->getImageDetails();
        $this->imageWidth       = $imageDetails[0];
        $this->imageHeight      = $imageDetails[1];
        $this->imageMimeType    = $this->image->getImageFile()->getMimeType();
        $this->imageSize        = $this->image->getImageFile()->getSize();
        $this->imageType        = 'default';
    }

    /**
     * Set image file
     * @param $image
     */
    public function setImage($image)
    {
        $this->image    = $image;
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
     * Create file name for image
     * @return string
     */
    public function createFileName(): string
    {
        $fileName = $this->image->getNameImage();
        if (isset($fileName))
        {
            $fileName = Urlizer::urlize($fileName).'-'.uniqid().'.'.$this->image->getImageFile()->guessExtension();
        } else {
            $originalFilename = pathinfo($this->image->getImageFile()->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$this->image->getImageFile()->guessExtension();
        }

        $this->fileName = $fileName;
        return $fileName;
    }

    /**
     * @param $destination
     * @param null $fileName
     */
    public function uploadImage($destination, $fileName)
    {
        $this->destination = $destination;
        $this->image->setNameImage($fileName);
        $this->image->getImageFile()->move($destination, $fileName);
    }


    public function createImageType(
        $type = 'original',
        $crop = 0,
        $original = 1
    )
    {
        $imageType = new ImageType();
        $imageType->setFolder($this->destination);
        $imageType->setHeight($this->imageHeight);
        $imageType->setWidth($this->imageWidth);
        $imageType->setIsCrop($crop);
        $imageType->setIsOriginal($original);
        $imageType->setType($type);
        $this->imageTypeRepository->save($imageType);

        return $imageType;
    }

    /**
     * @return array
     */
    public function processImageTypes()
    {
        $imageTypes = [];
        foreach ($this->params[$this->imageType]['image_types'] as $type => $imageType)
        {
            switch ($imageType['type_crop']) {
                case 'auto':
                    $this->
                    $imageType = $this->createImageType($imageType[$type], 1, 0);
                    $imageTypes[] = $imageType;
                    break;
                case 'manual':
                    echo "i equals 1";
                    break;
                case 'none':
                    $this->saveImageIntoFolder($imageType['folder']);
                    $imageType = $this->createImageType($imageType[$type], 0, 0);
                    $imageTypes[] = $imageType;
                    break;
            }
        }

        return $imageTypes;

    }

    /**
     * Get image details
     * @return array|false
     */
    private function getImageDetails()
    {
        return getimagesize($this->image->getImageFile()->getPathname());
    }

    /**
     * @param $subfolder
     */
    private function saveImageIntoFolder($subfolder)
    {
        $destinationFolder = $this->createDestinationFolder($subfolder);
        $fileName = $this->createFileName();
        $this->uploadImage($destinationFolder, $fileName);
    }
}