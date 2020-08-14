<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class UploadImagesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources'));

        $loader->load('services.yaml');
        $config = $this->processConfiguration(new Configuration(), $configs);

        var_dump($config); die;

        //$container->setParameter('upload_images.image.save_original', $config['image.save_original']);


    }
}