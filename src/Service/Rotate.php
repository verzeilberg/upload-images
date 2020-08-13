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

    public function Rotate()
    {
        $parameterValue = $this->params->get('parameter_name');
        die('fdsfsdfs');
    }

}