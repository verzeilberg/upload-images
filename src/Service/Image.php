<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use verzeilberg\UploadImagesBundle\Metadata\Reader\Annotation;

class Image
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;

        var_dump($params); die;

    }

    public function Upload($class)
    {
        $iets = new Annotation($class);
    }


    
}