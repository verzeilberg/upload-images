<?php
// src/Controller/Crop/CropController.php
namespace verzeilberg\UploadImagesBundle\Controller\Crop;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use verzeilberg\UploadImagesBundle\Form\Image\Crop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use verzeilberg\UploadImagesBundle\Service\Crop as CropService;
use verzeilberg\UploadImagesBundle\Service\Image as ImageService;

/**
 * @Route(service="test_service")
 */
class CropController extends AbstractController
{

    /** @var ImageService */
    private $cropService;
    /** @var ImageService  */
    private $imageService;

    /**
     * @param CropService $cropService
     * @param ImageService $imageService
     */
    public function __construct(
        CropService $cropService,
        ImageService $imageService
    )
    {
        $this->cropService  = $cropService;
        $this->imageService = $imageService;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function crop(Request $request)
    {
        $redirectUrl = $request->query->get('returnUrl');

        $imagesToBeCropped = $this->imageService->getImagesFromSession();

        //Get the first item in the array
        $imageToBeCropped           = array_values($imagesToBeCropped)[0];
        $projectLocation            = $imageToBeCropped['projectDirectory'];
        $imageOriginalLocation      = $imageToBeCropped['originalLocation'];
        $imageDestinationLocation   = $imageToBeCropped['destinationLocation'];
        $imageHeight                = $imageToBeCropped['height'];
        $imageWidth                 = $imageToBeCropped['width'];

        $form = $this->createForm(Crop::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $croppedX = $data['detailX'];
            $croppedY = $data['detailY'];
            $croppedH = $data['detailH'];
            $croppedW = $data['detailW'];

            $this->cropService->CropImage(
                $projectLocation . $imageOriginalLocation,
                $projectLocation . $imageDestinationLocation,
                $croppedX,
                $croppedY,
                $croppedW,
                $croppedH,
                $imageWidth,
                $imageHeight
            );

            # Delete the first item of the array
            array_shift($imagesToBeCropped);
            $this->imageService->setImagesIntoSession($imagesToBeCropped);

            # Check if the array is empty
            if (empty($imagesToBeCropped)) {
                $this->imageService->removeImagesFromSession();
                return $this->redirectToRoute($redirectUrl);
            } else {
                return $this->redirectToRoute('app_cropimage', ['returnUrl' => $redirectUrl]);
            }

        }

        return $this->render('@UploadImages/Crop/index.html.twig', [
            'sImageToBeCropped' => $imageOriginalLocation,
            'imageHeight'       => $imageHeight,
            'imageWidth'        => $imageWidth,
            'form'              => $form->createView(),
        ]);
    }
}
