<?php

namespace verzeilberg\UploadImagesBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Rotate
{

    private $params;
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function Rotate()
    {
var_dump($parameterValue = $this->params); die;

        return true;
    }

}