<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class UploadImagesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('upload_files.version', $config['version']);
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources'));
        $loader->load('services.yaml');

        /*$definition = $container->getDefinition('upload_images.rotate');
        $definition->setArgument(0, $config['version']);*/
    }
}