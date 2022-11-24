<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use verzeilberg\UploadImagesBundle\Entity\ImageType;
use verzeilberg\UploadImagesBundle\Exceptions\ImageException;
use verzeilberg\UploadImagesBundle\Metadata\Reader\Annotation;
use verzeilberg\UploadImagesBundle\Entity\Image as ImageObject;
use verzeilberg\UploadImagesBundle\Repository\ImageTypeRepository;
use verzeilberg\UploadImagesBundle\Repository\ImageRepository;
use verzeilberg\UploadImagesBundle\Service\Crop;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Image
{

    /** @var ImageRepository */
    private $imageRepository;
    /** @var ImageTypeRepository */
    private $imageTypeRepository;
    /** @var Crop */
    private $cropService;
    /** @var ParameterBagInterface */
    private $params;
    /** @var mixed */
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
    /** @var string */
    private $projectDir;
    /** @var object */
    private $session;

    /**
     * Image constructor.
     * @param ParameterBagInterface $params
     * @param ImageTypeRepository $imageTypeRepository
     */
    public function __construct(
        ParameterBagInterface $params,
        ImageRepository $imageRepository,
        ImageTypeRepository $imageTypeRepository,
        Crop $cropService,
        string $projectDir,
        SessionInterface $session
    )
    {
        $this->params = $params->get("upload_images");
        $this->imageRepository = $imageRepository;
        $this->imageTypeRepository = $imageTypeRepository;
        $this->cropService = $cropService;
        $this->projectDir = $projectDir;
        $this->session = $session;
    }

    /**
     * Set image properties into class variables
     */
    private function setImageProperties()
    {
        list($height, $width) = getimagesize($this->image->getImageFile()->getPathName());
        $this->imageWidth = $width;
        $this->imageHeight = $height;
        $this->imageMimeType = $this->image->getImageFile()->getMimeType();
        $this->imageSize = $this->image->getImageFile()->getSize();
        $this->imageType = 'default';
    }

    /**
     * Set image file
     * @param $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        $this->setImageProperties();
    }

    /**
     * Check if uploaded image sets the requirements
     * @return bool
     */
    public function check(): bool
    {
        if ($this->imageSize > $this->params[$this->imageType]['max_file_size']) {
            $message = 'File size exceeded max file size';
        } else if (!in_array($this->imageMimeType, $this->params[$this->imageType]['allowed_file_types'])) {
            $message = 'File mime type not allowed';
        } else {
            foreach ($this->params[$this->imageType]['image_types'] as $type => $imageType) {
                if (isset($imageType['ratio'])) {
                    if ($imageType['ratio']['width'] > $this->imageWidth) {
                        $message = 'File width is to small to create a Crop';
                    } else if ($imageType['ratio']['height'] > $this->imageHeight) {
                        $message = 'File height is to small to create a Crop';
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
    public function createDestinationFolder($subFolder = null): string
    {
        // Set destination folder
        $destinationFolder = $this->projectDir . $this->params[$this->imageType]['upload_folder'] . (isset($subFolder) ? $subFolder . '/' : null);
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
        if (isset($fileName)) {
            $extension = $this->image->getImageFile()->guessExtension();
            $fileName = Urlizer::urlize($fileName) . '-' . uniqid() . '.' . $extension;
        } else {
            $originalFilename = pathinfo($this->image->getImageFile()->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $this->image->getImageFile()->guessExtension();
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
        copy($this->image->getImageFile()->getPathName(), $destination . $fileName);
    }


    public function createImageType(
        $image,
        $type = 'original',
        $folder = 'original',
        $crop = 0,
        $original = 1

    )
    {
        $destination = $this->createDestinationFolder($folder);
        $destination = str_replace($this->projectDir, '', $destination);

        $imageType = new ImageType();
        $imageType->setFolder($destination);
        $imageType->setHeight($this->imageHeight);
        $imageType->setWidth($this->imageWidth);
        $imageType->setIsCrop($crop);
        $imageType->setIsOriginal($original);
        $imageType->setType($type);
        $imageType->setImage($image);
        $this->imageTypeRepository->save($imageType);

        return $imageType;
    }

    /**
     * @return array
     */
    public function processImageTypes($folder, $imageName)
    {
        $imageTypes = [];
        foreach ($this->params[$this->imageType]['image_types'] as $type => $imageType) {
            switch ($imageType['type_crop']) {
                case 'auto':
                    $this->cropService->resizeAndCropImage(
                        $this->projectDir . $folder . $imageName,
                        $this->projectDir . '/uploads/images/' . $type . '/' . $imageType['folder'],
                        $imageType['ratio']['width'],
                        $imageType['ratio']['height']
                    );
                    $imageType = $this->createImageType($this->image, $type, $imageType['folder'], 1, 0);
                    $imageTypes[] = $imageType;
                    break;
                case 'manual':
                    $this->saveImageIntoSession(
                        $type,
                        $this->projectDir . $folder . $imageName,
                        $this->projectDir . '/uploads/images/' . $type . '/' . $imageType['folder'],
                        $imageType['ratio']['width'],
                        $imageType['ratio']['height']
                        );
                    $imageType = $this->createImageType($this->image, $type, $imageType['folder'], 1, 0);
                    $imageTypes[] = $imageType;
                    break;
                case 'none':
                    $this->saveImageIntoFolder($imageType['folder'], $imageName);
                    $imageType = $this->createImageType($this->image, $type, $imageType['folder'], 0, 0);
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
        list($height, $width) = getimagesize($this->image->getImageFile()->getPathName());
        return [$height, $width];

    }

    /**
     * @param $image
     * @return array
     */
    public function saveImage($image): array
    {
        return $this->imageRepository->save($image);
    }

    /**
     * @param $subFolder
     * @param null $fileName
     */
    private function saveImageIntoFolder($subFolder, $fileName = null)
    {
        $destinationFolder = $this->createDestinationFolder($subFolder);
        if (!isset($fileName)) {
            $fileName = $this->createFileName();
        }
        $this->uploadImage($destinationFolder, $fileName);
    }

    /**
     * @param $type
     * @param $sOriLocation
     * @param $sDestinationFolder
     * @param $iImgWidth
     * @param $iImgHeight
     * @return void
     */
    private function saveImageIntoSession(
        $type,
        $sOriLocation = null,
        $sDestinationFolder = null,
        $iImgWidth = null,
        $iImgHeight = null
    )
    {

        $image = [
            'oriLocation'           => $sOriLocation,
            'destinationLocation'   => $sDestinationFolder,
            'width'                 => $iImgWidth,
            'height'                => $iImgHeight
        ];

        $manualImages = [];
        if($this->checkImageInSession()) {
            $manualImages = $this->session->get('manual_images');
            $manualImages[$type] = $image;
        } else {
            $manualImages[$type] = $image;
        }

        $this->session->set('manual_images', $manualImages);
    }

    /**
     * @return bool
     */
    public function checkImageInSession(): bool
    {
        if ($this->session->has('manual_images'))
        {
            return true;
        };

        return false;
    }

    /**
     * @return mixed
     */
    public function getImagesFromSession()
    {
        if ($this->checkImageInSession())
        {
            return $this->session->get('manual_images');
        } else {
            throw new ImageException('No images in session!');
        }
    }

    /**
     * Delete file from server
     * @param $fileName
     * @return bool
     */
    private function removeImageFromServer($fileName)
    {
        return @unlink($fileName);
    }

    /**
     * @param $id
     * @return void
     */
    public function deleteImage($id)
    {
        $image = $this->imageRepository->find($id);
        $result = false;
        foreach ($image->getImageTypes() as $imageType) {
            $result = $this->removeImageFromServer($this->projectDir . $imageType->getFolder() . $image->getNameImage());
            $this->imageTypeRepository->delete($imageType);
        }

        if ($result === true) {
            $this->imageRepository->delete($image);
        }

    }
}