<?php
// src/Controller/Crop/CropController.php
namespace verzeilberg\UploadImagesBundle\Controller\Crop;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use verzeilberg\UploadImagesBundle\Service\Image as ImageService;

class CropController extends AbstractController
{

    /** @var ImageService */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function crop($returnController)
    {
        $imagesToBeCropped = $this->imageService->getImagesFromSession();




        return $this->render('@UploadImages/Crop/index.html.twig', []);
    }
}
