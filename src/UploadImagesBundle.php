<?php


namespace verzeilberg\UploadImagesBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use verzeilberg\UploadImagesBundle\Service\Rotate;

class UploadImagesBundle extends Bundle
{

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new Rotate());

    }

}