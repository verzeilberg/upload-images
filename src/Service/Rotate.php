<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Rotate
{

    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function Rotate()
    {

        $username = $this->container->getParameter('save_original');
        var_dump($username);
        die('fdsfsdfs');
    }

}