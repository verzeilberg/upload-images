<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Rotate
{

    private $container; // <- Add this
    public function __construct(ContainerInterface $container) // <- Add this
    {
        $this->container = $container;
    }

    public function Rotate()
    {
var_dump($this->container); die;

        return true;
    }

}