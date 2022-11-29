<?php
// src/Controller/Crop/CropController.php
namespace verzeilberg\UploadImagesBundle\Controller\Crop;

use verzeilberg\UploadImagesBundle\Form\Image\Crop;
use Symfony\Component\HttpFoundation\Request;
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

    public function crop($returnController,Request $request)
    {
        $imagesToBeCropped = $this->imageService->getImagesFromSession();

        $sImageToBeCropped = $imagesToBeCropped['blogging2']['oriLocation'];

        $form = $this->createForm(Crop::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            var_dump($data); die;
        }


        return $this->render('@UploadImages/Crop/index.html.twig', [
            'sImageToBeCropped' => $sImageToBeCropped,
            'form' => $form->createView(),
        ]);
    }
}
