<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Rotate
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


    
}